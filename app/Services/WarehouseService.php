<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use App\Models\WarehouseOrder;
use App\Models\WarehouseOrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class WarehouseService
{
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
                'warehouse_id' => $warehouse->id,
                'order_type' => 'inbound',
                'status' => 'pending',
                'expected_date' => $po->delivery_date ?? now()->addDays(7),
            ]);

            foreach ($po->items as $poItem) {
                $order->items()->create([
                    'product_id' => $poItem->product_id,
                    'expected_quantity' => $poItem->quantity,
                    'received_quantity' => 0,
                    'status' => 'pending',
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
                    'received_quantity' => $itemData['received_quantity'],
                    'condition' => $itemData['condition'] ?? 'good',
                    'received_at' => now(),
                    'status' => 'received',
                ]);
            }

            // Check if all items have been received
            $allReceived = $order->items()->where('status', '!=', 'received')->doesntExist();

            if ($allReceived) {
                $order->update([
                    'status' => 'received',
                    'received_at' => now(),
                ]);
            } else {
                $order->update(['status' => 'partially_received']);
            }
        });
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
                'location_id' => $location->id,
                'status' => 'putaway',
                'putaway_at' => now(),
            ]);

            // Update location occupancy
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
            ->join('warehouse_locations', 'warehouse_order_items.location_id', '=', 'warehouse_locations.id')
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
                'status' => 'picked',
                'picked_at' => now(),
            ]);

            // Reduce location occupancy
            if ($item->location_id) {
                WarehouseLocation::where('id', $item->location_id)
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
            $order->items()->update([
                'status' => 'packed',
                'packed_at' => now(),
            ]);

            $order->update([
                'status' => 'packed',
                'packed_at' => now(),
            ]);
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
        if ($order->status !== 'packed') {
            throw new \InvalidArgumentException(
                'Order must be packed before it can be shipped. Current status: ' . $order->status
            );
        }

        $order->update([
            'status' => 'shipped',
            'tracking_number' => $trackingNumber,
            'shipped_at' => now(),
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
    public function processBarcodeScan(string $barcode, Warehouse $warehouse): array
    {
        // Try to match as a product SKU
        $product = \App\Models\Product::where('sku', $barcode)->first();
        if ($product) {
            $inventory = \App\Models\Inventory::where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->first();

            return [
                'type' => 'product',
                'data' => [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity_on_hand' => $inventory->quantity_on_hand ?? 0,
                    'quantity_available' => $inventory->quantity_available ?? 0,
                    'warehouse' => $warehouse->name,
                ],
            ];
        }

        // Try to match as a warehouse order number
        $order = WarehouseOrder::where('warehouse_id', $warehouse->id)
            ->whereHas('purchaseOrder', function ($query) use ($barcode) {
                $query->where('po_number', $barcode);
            })
            ->with('items.product')
            ->first();

        if ($order) {
            return [
                'type' => 'order',
                'data' => [
                    'order_id' => $order->id,
                    'purchase_order_number' => $barcode,
                    'status' => $order->status,
                    'items_count' => $order->items->count(),
                    'warehouse' => $warehouse->name,
                ],
            ];
        }

        // Try to match as a warehouse location code
        $location = WarehouseLocation::where('warehouse_id', $warehouse->id)
            ->where('code', $barcode)
            ->first();

        if ($location) {
            return [
                'type' => 'location',
                'data' => [
                    'location_id' => $location->id,
                    'code' => $location->code,
                    'zone' => $location->zone,
                    'aisle' => $location->aisle,
                    'rack' => $location->rack,
                    'shelf' => $location->shelf,
                    'current_quantity' => $location->current_quantity,
                    'max_capacity' => $location->max_capacity,
                    'warehouse' => $warehouse->name,
                ],
            ];
        }

        throw new \InvalidArgumentException(
            "Barcode '{$barcode}' could not be resolved to a product, order, or location in warehouse '{$warehouse->name}'."
        );
    }
}
