<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransfer;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Adjust stock for a product in a specific warehouse.
     *
     * Creates or updates the inventory record and logs the transaction.
     *
     * @param Product $product
     * @param Warehouse $warehouse
     * @param int $quantity Positive for additions, negative for deductions
     * @param string $type Transaction type (e.g., 'received', 'issued', 'adjustment', 'transfer_in', 'transfer_out', 'return')
     * @param string|null $notes Optional notes for the transaction
     * @return Inventory
     */
    public function adjustStock(
        Product $product,
        Warehouse $warehouse,
        float $quantity,
        string $type,
        ?string $notes = null
    ): Inventory {
        return DB::transaction(function () use ($product, $warehouse, $quantity, $type, $notes) {
            $inventory = Inventory::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                ],
                [
                    'quantity_on_hand' => 0,
                    'quantity_reserved' => 0,
                ]
            );

            $previousQuantity = $inventory->quantity_on_hand;
            $newQuantity = $previousQuantity + $quantity;

            if ($newQuantity < 0) {
                throw new \InvalidArgumentException(
                    "Insufficient stock. Available: {$previousQuantity}, Requested deduction: " . abs($quantity)
                );
            }

            $inventory->update([
                'quantity_on_hand' => $newQuantity,
            ]);

            // Log the inventory transaction
            InventoryTransaction::create([
                'inventory_id' => $inventory->id,
                'type'         => $type,
                'quantity'     => $quantity,
                'notes'        => $notes,
            ]);

            $inventory->refresh();

            return $inventory;
        });
    }

    /**
     * Get products that need reordering (quantity on hand at or below reorder point).
     *
     * @return Collection
     */
    public function getReorderAlerts(): Collection
    {
        return Inventory::with(['product', 'warehouse'])
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })
            ->whereRaw('quantity_on_hand <= (SELECT reorder_point FROM products WHERE products.id = inventory.product_id)')
            ->get()
            ->map(fn($inventory) => [
                'product_id'    => $inventory->product_id,
                'sku'           => $inventory->product->sku,
                'product'       => $inventory->product->name,
                'warehouse'     => $inventory->warehouse->name,
                'current_stock' => $inventory->quantity_on_hand,
                'reorder_point' => $inventory->product->reorder_point,
                'deficit'       => max(0, $inventory->product->reorder_point - $inventory->quantity_on_hand),
            ]);
    }

    /**
     * Process the shipment side of an inventory transfer (deduct from source warehouse).
     *
     * @param InventoryTransfer $transfer
     * @return void
     *
     * @throws \InvalidArgumentException If the transfer is not in approved status.
     */
    public function processTransferShipment(InventoryTransfer $transfer): void
    {
        if ($transfer->status !== 'approved') {
            throw new \InvalidArgumentException(
                "Transfer #{$transfer->transfer_number} must be approved before shipment. Current status: {$transfer->status}"
            );
        }

        DB::transaction(function () use ($transfer) {
            $transfer->load('items');

            foreach ($transfer->items as $item) {
                $this->adjustStock(
                    Product::findOrFail($item->product_id),
                    Warehouse::findOrFail($transfer->from_warehouse_id),
                    -$item->quantity_requested,
                    'transfer_out',
                    "Transfer #{$transfer->transfer_number} shipment to warehouse #{$transfer->to_warehouse_id}"
                );
            }

            $transfer->update([
                'status' => 'in_transit',
                'shipped_at' => now(),
            ]);
        });
    }

    /**
     * Process the receipt side of an inventory transfer (add to destination warehouse).
     *
     * @param InventoryTransfer $transfer
     * @return void
     *
     * @throws \InvalidArgumentException If the transfer is not in in_transit status.
     */
    public function processTransferReceipt(InventoryTransfer $transfer): void
    {
        if ($transfer->status !== 'in_transit') {
            throw new \InvalidArgumentException(
                "Transfer #{$transfer->transfer_number} must be in transit before receipt. Current status: {$transfer->status}"
            );
        }

        DB::transaction(function () use ($transfer) {
            $transfer->load('items');

            foreach ($transfer->items as $item) {
                $this->adjustStock(
                    Product::findOrFail($item->product_id),
                    Warehouse::findOrFail($transfer->to_warehouse_id),
                    $item->quantity_requested,
                    'transfer_in',
                    "Transfer #{$transfer->transfer_number} received from warehouse #{$transfer->from_warehouse_id}"
                );
            }

            $transfer->update([
                'status' => 'completed',
                'received_at' => now(),
            ]);
        });
    }

    /**
     * Get stock levels for a product across all warehouses.
     *
     * @param Product $product
     * @return Collection
     */
    public function getStockAcrossWarehouses(Product $product): Collection
    {
        return Inventory::with('warehouse')
            ->where('product_id', $product->id)
            ->get();
    }
}
