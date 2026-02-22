<?php
namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GoodsReceiptController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService) {}

    public function index(): View
    {
        $receipts = GoodsReceipt::with(['purchaseOrder.vendor', 'receiver'])->latest()->paginate(15);
        return view('procurement.goods-receipts.index', compact('receipts'));
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
            'purchase_order_id'              => 'required|exists:purchase_orders,id',
            'received_at'                    => 'required|date',
            'notes'                          => 'nullable|string',
            'items'                          => 'required|array|min:1',
            'items.*.purchase_order_item_id' => 'required|exists:purchase_order_items,id',
            'items.*.quantity_received'      => 'required|numeric|min:0',
            'items.*.quantity_accepted'      => 'required|numeric|min:0',
            'items.*.quantity_rejected'      => 'nullable|numeric|min:0',
            'items.*.rejection_reason'       => 'nullable|string|max:255',
        ]);

        $receipt = GoodsReceipt::create([
            'purchase_order_id' => $validated['purchase_order_id'],
            'received_by'       => auth()->id(),
            'received_at'       => $validated['received_at'],
            'status'            => 'complete',
            'notes'             => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $receipt->items()->create($item);
        }

        $this->syncInventoryAndPoStatus($receipt);

        return redirect()->route('procurement.goods-receipts.show', $receipt)->with('success', 'Goods receipt created.');
    }

    public function show(GoodsReceipt $goodsReceipt): View
    {
        $goodsReceipt->load(['purchaseOrder.vendor', 'items.purchaseOrderItem.product', 'receiver']);
        $receipt = $goodsReceipt;
        return view('procurement.goods-receipts.show', compact('receipt'));
    }

    public function edit(GoodsReceipt $goodsReceipt): View
    {
        $receipt = $goodsReceipt;
        return view('procurement.goods-receipts.edit', compact('receipt'));
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

    private function syncInventoryAndPoStatus(GoodsReceipt $receipt): void
    {
        $receipt->load(['items.purchaseOrderItem.product', 'purchaseOrder.items']);

        $po = $receipt->purchaseOrder;
        $warehouseOrder = \App\Models\WarehouseOrder::where('purchase_order_id', $po->id)
            ->with('warehouse')
            ->first();
        $warehouse = $warehouseOrder?->warehouse
            ?? Warehouse::where('is_active', true)->first();

        if (!$warehouse) return;

        foreach ($receipt->items as $receiptItem) {
            $poItem  = $receiptItem->purchaseOrderItem;
            $product = $poItem?->product;

            if ($product && $receiptItem->quantity_accepted > 0) {
                $this->inventoryService->adjustStock(
                    $product,
                    $warehouse,
                    (float) $receiptItem->quantity_accepted,
                    'received',
                    "Goods Receipt {$receipt->receipt_number} — PO {$po->po_number}"
                );
            }

            if ($poItem) {
                $totalReceived = GoodsReceiptItem::whereHas('goodsReceipt', fn($q) => $q->where('purchase_order_id', $po->id))
                    ->where('purchase_order_item_id', $poItem->id)
                    ->sum('quantity_accepted');
                $poItem->update(['received_quantity' => $totalReceived]);
            }
        }

        $po->refresh()->load('items');
        $fullyReceived    = $po->items->every(fn($i) => $i->received_quantity >= $i->quantity);
        $partiallyReceived = $po->items->some(fn($i) => $i->received_quantity > 0);

        if ($fullyReceived) {
            $po->update(['status' => 'received']);
        } elseif ($partiallyReceived) {
            $po->update(['status' => 'partially_received']);
        }
    }
}
