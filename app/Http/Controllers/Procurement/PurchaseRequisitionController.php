<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseRequisition;
use App\Services\ProcurementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseRequisitionController extends Controller
{
    public function __construct(
        private readonly ProcurementService $procurementService
    ) {}

    public function index(Request $request): View
    {
        $requisitions = PurchaseRequisition::query()
            ->when($request->input('status'), fn ($query, $status) => $query->where('status', $status))
            ->when($request->input('priority'), fn ($query, $priority) => $query->where('priority', $priority))
            ->latest()
            ->paginate(15);

        return view('procurement.requisitions.index', compact('requisitions'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('procurement.requisitions.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'required_date' => 'required|date|after:today',
            'priority' => 'nullable|in:low,medium,high,critical',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.estimated_unit_price' => 'required|numeric|min:0',
            'items.*.specifications' => 'nullable|string',
        ]);

        $items = $validated['items'];
        $data = array_merge(
            array_diff_key($validated, ['items' => null]),
            ['requested_by' => auth()->id()]
        );

        $requisition = $this->procurementService->createRequisition($data, $items);

        return redirect()
            ->route('procurement.requisitions.show', $requisition)
            ->with('success', 'Purchase requisition created successfully.');
    }

    public function show(PurchaseRequisition $requisition): View
    {
        $requisition->load(['items.product', 'creator', 'approver']);

        return view('procurement.requisitions.show', compact('requisition'));
    }

    public function edit(PurchaseRequisition $requisition): View
    {
        $requisition->load('items');

        return view('procurement.requisitions.edit', compact('requisition'));
    }

    public function update(Request $request, PurchaseRequisition $requisition): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'required_date' => 'required|date|after:today',
            'priority' => 'nullable|in:low,medium,high,critical',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.specifications' => 'nullable|string',
        ]);

        $this->procurementService->updateRequisition($requisition, $validated);

        return redirect()
            ->route('procurement.requisitions.show', $requisition)
            ->with('success', 'Purchase requisition updated successfully.');
    }

    public function destroy(PurchaseRequisition $requisition): RedirectResponse
    {
        $requisition->delete();

        return redirect()
            ->route('procurement.requisitions.index')
            ->with('success', 'Purchase requisition deleted successfully.');
    }

    public function submit(PurchaseRequisition $requisition): RedirectResponse
    {
        if ($requisition->status?->value !== 'draft') {
            return back()->with('error', 'Only draft requisitions can be submitted for approval.');
        }

        $requisition->update(['status' => 'pending_approval']);

        return redirect()
            ->route('procurement.requisitions.show', $requisition)
            ->with('success', 'Requisition submitted for approval.');
    }

    public function approve(Request $request, PurchaseRequisition $requisition): RedirectResponse
    {
        $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        $this->procurementService->approveRequisition($requisition, auth()->user());

        return redirect()
            ->route('procurement.requisitions.show', $requisition)
            ->with('success', 'Purchase requisition approved successfully.');
    }

    public function reject(Request $request, PurchaseRequisition $requisition): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $this->procurementService->rejectRequisition($requisition, auth()->user(), $request->input('rejection_reason'));

        return redirect()
            ->route('procurement.requisitions.show', $requisition)
            ->with('success', 'Purchase requisition rejected.');
    }

    public function convertToPo(Request $request, PurchaseRequisition $requisition): RedirectResponse
    {
        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'delivery_date' => 'nullable|date|after:today',
            'payment_terms' => 'nullable|string|max:255',
        ]);

        $purchaseOrder = $this->procurementService->convertRequisitionToPo($requisition, (int) $request->input('vendor_id'));

        return redirect()
            ->route('procurement.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase requisition converted to purchase order successfully.');
    }
}
