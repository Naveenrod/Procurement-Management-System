<?php
namespace App\Http\Controllers\SupplierPortal;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierInvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::where('vendor_id', auth()->user()->vendor_id)->latest()->paginate(15);
        return view('supplier.invoices.index', compact('invoices'));
    }

    public function create(PurchaseOrder $purchaseOrder): View
    {
        abort_unless($purchaseOrder->vendor_id === auth()->user()->vendor_id, 403);
        $purchaseOrder->load('items.product');
        return view('supplier.invoices.create', compact('purchaseOrder'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['purchase_order_id' => 'required|exists:purchase_orders,id', 'invoice_date' => 'required|date', 'due_date' => 'required|date', 'notes' => 'nullable|string', 'items' => 'required|array|min:1', 'items.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id', 'items.*.description' => 'required|string', 'items.*.quantity' => 'required|numeric|min:0', 'items.*.unit_price' => 'required|numeric|min:0']);
        $po = PurchaseOrder::find($validated['purchase_order_id']);
        abort_unless($po->vendor_id === auth()->user()->vendor_id, 403);
        $items = collect($validated['items'])->map(fn($i) => array_merge($i, ['total_price' => $i['quantity'] * $i['unit_price']]));
        $subtotal = $items->sum('total_price');
        $invoice = Invoice::create(['vendor_id' => $po->vendor_id, 'purchase_order_id' => $po->id, 'invoice_date' => $validated['invoice_date'], 'due_date' => $validated['due_date'], 'subtotal' => $subtotal, 'total_amount' => $subtotal, 'submitted_by' => auth()->id(), 'notes' => $validated['notes'] ?? null]);
        foreach ($items as $item) { $invoice->items()->create($item); }
        return redirect()->route('supplier.invoices.show', $invoice)->with('success', 'Invoice submitted.');
    }

    public function show(Invoice $invoice): View
    {
        abort_unless($invoice->vendor_id === auth()->user()->vendor_id, 403);
        $invoice->load(['purchaseOrder', 'items.purchaseOrderItem.product']);
        return view('supplier.invoices.show', compact('invoice'));
    }
}
