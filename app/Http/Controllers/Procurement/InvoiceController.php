<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Notifications\InvoiceStatusChanged;
use App\Services\ThreeWayMatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private readonly ThreeWayMatchService $matchService) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Invoice::class);

        $invoices = Invoice::with('vendor')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('procurement.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $this->authorize('create', Invoice::class);

        $purchaseOrders = PurchaseOrder::whereIn('status', ['sent', 'approved', 'received', 'acknowledged'])->with('vendor')->get();
        $vendors = Vendor::where('status', 'approved')->orderBy('name')->get();
        return view('procurement.invoices.create', compact('purchaseOrders', 'vendors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Invoice::class);

        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'goods_receipt_id' => 'nullable|exists:goods_receipts,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $subtotal = $validated['subtotal'];
        $taxAmount = $validated['tax_amount'] ?? 0;

        $invoice = Invoice::create(array_merge($validated, [
            'tax_amount' => $taxAmount,
            'total_amount' => $subtotal + $taxAmount,
            'status' => 'pending',
            'three_way_match_status' => 'pending',
            'submitted_by' => auth()->id(),
        ]));

        return redirect()->route('procurement.invoices.show', $invoice)->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice): View
    {
        $this->authorize('view', $invoice);

        $invoice->load(['vendor', 'purchaseOrder', 'goodsReceipt', 'items.purchaseOrderItem.product']);
        return view('procurement.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $this->authorize('update', $invoice);

        $invoice->load('items');
        return view('procurement.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $this->authorize('update', $invoice);

        $invoice->update($request->only(['notes', 'due_date']));
        return redirect()->route('procurement.invoices.show', $invoice)->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->authorize('delete', $invoice);

        $invoice->delete();
        return redirect()->route('procurement.invoices.index')->with('success', 'Invoice deleted.');
    }

    public function threeWayMatch(Invoice $invoice): RedirectResponse
    {
        $this->authorize('threeWayMatch', $invoice);

        $result = $this->matchService->performMatch($invoice);
        $matched = $result['status'] === 'matched';
        $invoice->update(['three_way_match_status' => $result['status']]);
        $msg = $matched ? 'Three-way match passed.' : 'Three-way match failed.';
        return redirect()->route('procurement.invoices.show', $invoice)->with($matched ? 'success' : 'error', $msg);
    }

    public function exportPdf(Invoice $invoice): \Illuminate\Http\Response
    {
        $this->authorize('exportPdf', $invoice);

        $invoice->load(['vendor', 'purchaseOrder', 'items.purchaseOrderItem.product', 'submitter', 'approver']);
        $pdf = Pdf::loadView('procurement.invoices.pdf', compact('invoice'));
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    public function approve(Invoice $invoice): RedirectResponse
    {
        $this->authorize('approve', $invoice);

        $invoice->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        $invoice->refresh();

        // Notify the submitter
        $submitter = User::find($invoice->submitted_by);
        $submitter?->notify(new InvoiceStatusChanged($invoice));

        return redirect()->route('procurement.invoices.show', $invoice)->with('success', 'Invoice approved.');
    }
}
