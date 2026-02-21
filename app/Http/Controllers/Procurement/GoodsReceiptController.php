<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoodsReceiptController extends Controller
{
    public function index(): View
    {
        $goodsReceipts = GoodsReceipt::with(['purchaseOrder.vendor', 'receiver'])->latest()->paginate(15);
        return view('procurement.goods-receipts.index', compact('goodsReceipts'));
    }

    public function create(Request $request): View
    {
        $purchaseOrders = PurchaseOrder::whereIn('status', ['sent', 'approved', 'acknowledged'])->with('vendor')->get();
        $selectedPo = $request->po_id ? PurchaseOrder::with('items.product')->find($request->po_id) : null;
        return view('procurement.goods-receipts.create', compact('purchaseOrders', 'selectedPo'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received' => 'required|numeric|min:0',
            'items.*.quantity_accepted' => 'required|numeric|min:0',
            'items.*.quantity_rejected' => 'nullable|numeric|min:0',
        ]);

        $receipt = GoodsReceipt::create([
            'purchase_order_id' => $validated['purchase_order_id'],
            'received_by' => auth()->id(),
            'received_at' => now(),
            'status' => 'complete',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $receipt->items()->create($item);
        }

        return redirect()->route('procurement.goods-receipts.show', $receipt)->with('success', 'Goods receipt created.');
    }

    public function show(GoodsReceipt $goodsReceipt): View
    {
        $goodsReceipt->load(['purchaseOrder.vendor', 'items.purchaseOrderItem.product', 'receiver']);
        return view('procurement.goods-receipts.show', compact('goodsReceipt'));
    }

    public function edit(GoodsReceipt $goodsReceipt): View
    {
        return view('procurement.goods-receipts.edit', compact('goodsReceipt'));
    }

    public function update(Request $request, GoodsReceipt $goodsReceipt): RedirectResponse
    {
        $goodsReceipt->update($request->only(['notes', 'status']));
        return redirect()->route('procurement.goods-receipts.show', $goodsReceipt)->with('success', 'Receipt updated.');
    }

    public function destroy(GoodsReceipt $goodsReceipt): RedirectResponse
    {
        $goodsReceipt->delete();
        return redirect()->route('procurement.goods-receipts.index')->with('success', 'Receipt deleted.');
    }
}
