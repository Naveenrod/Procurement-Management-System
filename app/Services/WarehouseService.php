<?php

namespace App\Services;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Models\WarehouseOrder;
use App\Models\WarehouseOrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WarehouseService
{
    public function __construct(private readonly InventoryService $inventoryService) {}
    /**
     * Create an inbound warehouse order from a purchase order.
     *
     * @param PurchaseOrder $po
     * @param Warehouse $warehouse
     * @return WarehouseOrder
     */
    public function createInboundOrder(PurchaseOrder $po, Warehouse $warehouse): WarehouseOrder
    {
        return DB::transaction(function () use ($po, $warehouse) {
            $po->load('items');

            $order = WarehouseOrder::create([
                'purchase_order_id' => $po->id,
                'warehouse_id'      => $warehouse->id,
                'type'              => 'inbound',
                'status'            => 'pending',
            ]);

            foreach ($po->items as $poItem) {
                $order->items()->create([
                    'product_id'        => $poItem->product_id,
                    'expected_quantity' => $poItem->quantity,
                    'status'            => 'pending',
                ]);
            }

            return $order;
        });
    }

    /**
     * Process receiving of items for a warehouse order.
     *
     * @param WarehouseOrder $order
     * @param array $items Array of received items [['warehouse_order_item_id' => int, 'received_quantity' => int, 'condition' => string], ...]
     * @return void
     */
    public function processReceiving(WarehouseOrder $order, array $items): void
    {
        DB::transaction(function () use ($order, $items) {
            foreach ($items as $itemData) {
                $orderItem = WarehouseOrderItem::findOrFail($itemData['warehouse_order_item_id']);

                $orderItem->update([
                    'received_quantity' => $itemData['received_quantity'] ?? $orderItem->expected_quantity,
                    'status'            => 'received',
                ]);
            }

            $allReceived = $order->items()->where('status', '!=', 'received')->doesntExist();
            $order->update(['status' => $allReceived ? 'putaway' : 'receiving']);

            // Auto-create a Goods Receipt and update inventory if linked to a PO
            if ($order->purchase_order_id) {
                $this->createGoodsReceiptFromWarehouseOrder($order);
            }
        });
    }

    private function createGoodsReceiptFromWarehouseOrder(WarehouseOrder $order): void
    {
        $order->load(['items.product', 'purchaseOrder.items', 'warehouse']);
        $po        = $order->purchaseOrder;
        $warehouse = $order->warehouse;

        if (!$po || !$warehouse) return;

        $receipt = GoodsReceipt::create([
            'purchase_order_id' => $po->id,
            'received_by'       => auth()->id(),
            'received_at'       => now(),
            'status'            => 'complete',
            'notes'             => "Auto-created from Warehouse Order {$order->order_number}",
        ]);

        foreach ($order->items as $orderItem) {
            $receivedQty = (float) ($orderItem->received_quantity ?? $orderItem->expected_quantity);
            if ($receivedQty <= 0) continue;

            $poItem = $po->items->firstWhere('product_id', $orderItem->product_id);
            if (!$poItem) continue;

            GoodsReceiptItem::create([
                'goods_receipt_id'       => $receipt->id,
                'purchase_order_item_id' => $poItem->id,
                'quantity_received'      => $receivedQty,
                'quantity_accepted'      => $receivedQty,
                'quantity_rejected'      => 0,
            ]);

            // Update inventory
            $this->inventoryService->adjustStock(
                $orderItem->product,
                $warehouse,
                $receivedQty,
                'received',
                "Warehouse Order {$order->order_number} — PO {$po->po_number}"
            );

            // Update PO item received_quantity
            $totalReceived = GoodsReceiptItem::whereHas('goodsReceipt', fn($q) => $q->where('purchase_order_id', $po->id))
                ->where('purchase_order_item_id', $poItem->id)
                ->sum('quantity_accepted');
            $poItem->update(['received_quantity' => $totalReceived]);
        }

        // Update PO status
        $po->refresh()->load('items');
        $fullyReceived     = $po->items->every(fn($i) => $i->received_quantity >= $i->quantity);
        $partiallyReceived = $po->items->some(fn($i) => $i->received_quantity > 0);

        if ($fullyReceived) {
            $po->update(['status' => 'received']);
        } elseif ($partiallyReceived) {
            $po->update(['status' => 'partially_received']);
        }
    }

    /**
     * Process putaway: assign a received item to a specific warehouse location.
     *
     * @param WarehouseOrderItem $item
     * @param WarehouseLocation $location
     * @return void
     */
    public function processPutaway(WarehouseOrderItem $item, WarehouseLocation $location): void
    {
        DB::transaction(function () use ($item, $location) {
            $item->update([
                'warehouse_location_id' => $location->id,
                'status'                => 'putaway',
            ]);

            $location->increment('current_quantity', $item->received_quantity);
        });
    }

    /**
     * Generate a pick list for an outbound warehouse order, sorted by location for efficiency.
     *
     * @param WarehouseOrder $order
     * @return Collection
     */
    public function generatePickList(WarehouseOrder $order): Collection
    {
        return $order->items()
            ->with(['product', 'location'])
            ->leftJoin('warehouse_locations', 'warehouse_order_items.warehouse_location_id', '=', 'warehouse_locations.id')
            ->orderBy('warehouse_locations.zone')
            ->orderBy('warehouse_locations.aisle')
            ->orderBy('warehouse_locations.rack')
            ->orderBy('warehouse_locations.shelf')
            ->select('warehouse_order_items.*')
            ->get();
    }

    /**
     * Process picking of an item from its warehouse location.
     *
     * @param WarehouseOrderItem $item
     * @param int $quantity The quantity actually picked
     * @return void
     *
     * @throws \InvalidArgumentException If picked quantity exceeds expected quantity.
     */
    public function processPicking(WarehouseOrderItem $item, int $quantity): void
    {
        if ($quantity > $item->expected_quantity) {
            throw new \InvalidArgumentException(
                "Picked quantity ({$quantity}) exceeds expected quantity ({$item->expected_quantity})."
            );
        }

        DB::transaction(function () use ($item, $quantity) {
            $item->update([
                'picked_quantity' => $quantity,
                'status'          => 'picked',
            ]);

            if ($item->warehouse_location_id) {
                WarehouseLocation::where('id', $item->warehouse_location_id)
                    ->decrement('current_quantity', $quantity);
            }
        });
    }

    /**
     * Process packing: mark all picked items as packed for the order.
     *
     * @param WarehouseOrder $order
     * @return void
     *
     * @throws \InvalidArgumentException If not all items have been picked.
     */
    public function processPacking(WarehouseOrder $order): void
    {
        $unpickedItems = $order->items()->where('status', '!=', 'picked')->exists();

        if ($unpickedItems) {
            throw new \InvalidArgumentException(
                'All items must be picked before packing can begin.'
            );
        }

        DB::transaction(function () use ($order) {
            $order->items()->update(['status' => 'packed']);
            $order->update(['status' => 'packing']);
        });
    }

    /**
     * Process shipping: mark the order as shipped with an optional tracking number.
     *
     * @param WarehouseOrder $order
     * @param string|null $trackingNumber
     * @return void
     *
     * @throws \InvalidArgumentException If the order is not packed.
     */
    public function processShipping(WarehouseOrder $order, ?string $trackingNumber = null): void
    {
        if ($order->getRawOriginal('status') !== 'packing') {
            throw new \InvalidArgumentException(
                'Order must be packed before it can be shipped. Current status: ' . $order->getRawOriginal('status')
            );
        }

        $notes = $trackingNumber ? ($order->notes ? $order->notes . ' | Tracking: ' . $trackingNumber : 'Tracking: ' . $trackingNumber) : $order->notes;

        $order->update([
            'status' => 'shipped',
            'notes'  => $notes,
        ]);

        $order->items()->update(['status' => 'shipped']);
    }

    /**
     * Process a barcode scan and return product/order information.
     *
     * @param string $barcode The scanned barcode (could be SKU, order number, or location code)
     * @param Warehouse $warehouse
     * @return array{type: string, data: mixed}
     *
     * @throws \InvalidArgumentException If the barcode cannot be resolved.
     */
    public function processBarcodeScan(string $barcode, ?Warehouse $warehouse = null): array
    {
        // Try to match as a product SKU
        $product = \App\Models\Product::where('sku', $barcode)->first();
        if ($product) {
            $query = \App\Models\Inventory::where('product_id', $product->id);
            if ($warehouse) {
                $query->where('warehouse_id', $warehouse->id);
            }
            $totalStock = $query->sum('quantity_on_hand');

            return [
                'product' => $product->name,
                'sku'     => $product->sku,
                'stock'   => number_format($totalStock, 2) . ' ' . $product->unit_of_measure,
            ];
        }

        // Try to match as a warehouse order number
        $orderQuery = WarehouseOrder::whereHas('purchaseOrder', function ($query) use ($barcode) {
            $query->where('po_number', $barcode);
        })->with('items');
        if ($warehouse) {
            $orderQuery->where('warehouse_id', $warehouse->id);
        }
        $order = $orderQuery->first();
        if ($order) {
            return [
                'product' => 'Purchase Order: ' . $barcode,
                'sku'     => $barcode,
                'stock'   => $order->items->count() . ' line item(s) — Status: ' . $order->status,
            ];
        }

        // Try to match as a warehouse location code
        $locationQuery = WarehouseLocation::where('code', $barcode);
        if ($warehouse) {
            $locationQuery->where('warehouse_id', $warehouse->id);
        }
        $location = $locationQuery->first();
        if ($location) {
            return [
                'product' => 'Location: ' . $location->code,
                'sku'     => $location->code,
                'stock'   => ($location->current_quantity ?? 0) . ' / ' . ($location->max_capacity ?? '—'),
            ];
        }

        return ['message' => "No product, order, or location found for barcode \"{$barcode}\"."];
    }
}
