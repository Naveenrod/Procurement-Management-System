<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Services\ThreeWayMatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(private readonly ThreeWayMatchService $matchService) {}

    public function index(Request $request): View
    {
        $invoices = Invoice::with('vendor')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);
        return view('procurement.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $purchaseOrders = PurchaseOrder::whereIn('status', ['sent', 'approved', 'received', 'acknowledged'])->with('vendor')->get();
        return view('procurement.invoices.create', compact('purchaseOrders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'goods_receipt_id' => 'nullable|exists:goods_receipts,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $items = collect($validated['items'])->map(fn($i) => array_merge($i, ['total_price' => $i['quantity'] * $i['unit_price']]));
        $subtotal = $items->sum('total_price');

        $invoice = Invoice::create(array_merge($validated, ['subtotal' => $subtotal, 'total_amount' => $subtotal, 'submitted_by' => auth()->id()]));
        foreach ($items as $item) { $invoice->items()->create($item); }

        return redirect()->route('procurement.invoices.show', $invoice)->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['vendor', 'purchaseOrder', 'goodsReceipt', 'items.purchaseOrderItem.product']);
        return view('procurement.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');
        return view('procurement.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($request->only(['notes', 'due_date']));
        return redirect()->route('procurement.invoices.show', $invoice)->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();
        return redirect()->route('procurement.invoices.index')->with('success', 'Invoice deleted.');
    }

    public function threeWayMatch(Invoice $invoice): RedirectResponse
    {
        $result = $this->matchService->performMatch($invoice);
        $status = $result['matched'] ? 'matched' : 'mismatch';
        $invoice->update(['three_way_match_status' => $status]);
        $msg = $result['matched'] ? 'Three-way match passed.' : 'Three-way match failed.';
        return redirect()->route('procurement.invoices.show', $invoice)->with($result['matched'] ? 'success' : 'error', $msg);
    }

    public function approve(Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        return redirect()->route('procurement.invoices.show', $invoice)->with('success', 'Invoice approved.');
    }
}
