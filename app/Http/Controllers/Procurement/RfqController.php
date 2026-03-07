<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Rfq;
use App\Models\Vendor;
use App\Models\Product;
use App\Services\ProcurementService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RfqController extends Controller
{
    public function __construct(private readonly ProcurementService $procurementService) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Rfq::class);

        $rfqs = Rfq::with('issuer')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('procurement.rfqs.index', compact('rfqs'));
    }

    public function create(): View
    {
        $this->authorize('create', Rfq::class);

        $vendors = Vendor::where('status', 'approved')->get();
        $products = Product::orderBy('name')->get();
        return view('procurement.rfqs.create', compact('vendors', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Rfq::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'closing_date' => 'required|date|after:today',
            'vendor_ids' => 'required|array|min:1',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
        ]);
        $rfq = Rfq::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'closing_date' => $validated['closing_date'],
            'issue_date' => now()->toDateString(),
            'issued_by' => auth()->id(),
        ]);
        foreach ($validated['vendor_ids'] as $vendorId) {
            $rfq->vendors()->create(['vendor_id' => $vendorId, 'invited_at' => now()]);
        }
        foreach ($validated['items'] as $item) { $rfq->items()->create($item); }
        return redirect()->route('procurement.rfqs.show', $rfq)->with('success', 'RFQ created successfully.');
    }

    public function show(Rfq $rfq): View
    {
        $this->authorize('view', $rfq);

        $rfq->load(['vendors', 'items.product', 'responses.vendor', 'responses.items', 'issuer']);
        return view('procurement.rfqs.show', compact('rfq'));
    }

    public function edit(Rfq $rfq): View
    {
        $this->authorize('update', $rfq);

        $vendors = Vendor::where('status', 'approved')->get();
        $products = Product::orderBy('name')->get();
        $rfq->load(['vendors', 'items']);
        return view('procurement.rfqs.edit', compact('rfq', 'vendors', 'products'));
    }

    public function update(Request $request, Rfq $rfq): RedirectResponse
    {
        $this->authorize('update', $rfq);

        $rfq->update($request->validate(['title' => 'required|string', 'description' => 'nullable|string', 'closing_date' => 'required|date']));
        return redirect()->route('procurement.rfqs.show', $rfq)->with('success', 'RFQ updated.');
    }

    public function destroy(Rfq $rfq): RedirectResponse
    {
        $this->authorize('delete', $rfq);

        $rfq->delete();
        return redirect()->route('procurement.rfqs.index')->with('success', 'RFQ deleted.');
    }

    public function exportPdf(Rfq $rfq): \Illuminate\Http\Response
    {
        $this->authorize('exportPdf', $rfq);

        $rfq->load(['vendors.vendor', 'items.product', 'responses.vendor', 'responses.items', 'issuer']);
        $pdf = Pdf::loadView('procurement.rfqs.pdf', compact('rfq'));
        return $pdf->download($rfq->rfq_number . '.pdf');
    }

    public function publish(Rfq $rfq): RedirectResponse
    {
        $this->authorize('publish', $rfq);

        $rfq->update(['status' => 'published']);
        return redirect()->route('procurement.rfqs.show', $rfq)->with('success', 'RFQ published to vendors.');
    }

    public function close(Rfq $rfq): RedirectResponse
    {
        $this->authorize('close', $rfq);

        $rfq->update(['status' => 'closed']);
        return redirect()->route('procurement.rfqs.show', $rfq)->with('success', 'RFQ closed.');
    }

    public function award(Request $request, Rfq $rfq): RedirectResponse
    {
        $this->authorize('award', $rfq);

        $request->validate(['response_id' => 'required|exists:rfq_responses,id']);
        $rfq->responses()->update(['is_selected' => false]);
        $rfq->responses()->where('id', $request->response_id)->update(['is_selected' => true]);
        $rfq->update(['status' => 'awarded']);
        return redirect()->route('procurement.rfqs.show', $rfq)->with('success', 'RFQ awarded.');
    }
}
