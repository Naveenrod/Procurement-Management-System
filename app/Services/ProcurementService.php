<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProcurementService
{
    /**
     * Create a new purchase requisition with its line items.
     *
     * @param array $data Requisition header data (requisition_number, requested_by, department, priority, required_date, notes, etc.)
     * @param array $items Array of line items [['product_id' => int, 'quantity' => int, 'unit_price' => float, 'specifications' => string], ...]
     * @return PurchaseRequisition
     */
    public function createRequisition(array $data, array $items): PurchaseRequisition
    {
        return DB::transaction(function () use ($data, $items) {
            $requisition = PurchaseRequisition::create(array_merge($data, [
                'status' => 'draft',
                'total_amount' => 0,
            ]));

            $totalAmount = 0;

            foreach ($items as $itemData) {
                $lineTotal = $itemData['quantity'] * $itemData['estimated_unit_price'];
                $totalAmount += $lineTotal;

                $requisition->items()->create(array_merge($itemData, [
                    'total_price' => $lineTotal,
                ]));
            }

            $requisition->update(['total_amount' => $totalAmount]);
            $requisition->refresh();

            return $requisition;
        });
    }

    /**
     * Update an existing purchase requisition and its line items.
     *
     * @param PurchaseRequisition $requisition
     * @param array $data Validated data including 'items' key
     * @return PurchaseRequisition
     */
    public function updateRequisition(PurchaseRequisition $requisition, array $data): PurchaseRequisition
    {
        return DB::transaction(function () use ($requisition, $data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $requisition->update($data);

            // Replace items
            $requisition->items()->delete();
            $totalAmount = 0;
            foreach ($items as $itemData) {
                $lineTotal = ($itemData['quantity'] ?? 0) * ($itemData['unit_price'] ?? $itemData['estimated_unit_price'] ?? 0);
                $totalAmount += $lineTotal;
                $requisition->items()->create([
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'estimated_unit_price' => $itemData['unit_price'] ?? $itemData['estimated_unit_price'] ?? 0,
                    'total_price' => $lineTotal,
                    'specifications' => $itemData['specifications'] ?? null,
                ]);
            }
            $requisition->update(['total_amount' => $totalAmount]);
            $requisition->refresh();

            return $requisition;
        });
    }

    /**
     * Approve a purchase requisition.
     *
     * @param PurchaseRequisition $requisition
     * @param User $approver
     * @return PurchaseRequisition
     *
     * @throws \InvalidArgumentException If the requisition is not in pending_approval status.
     */
    public function approveRequisition(PurchaseRequisition $requisition, User $approver): PurchaseRequisition
    {
        if ($requisition->status !== 'pending_approval') {
            throw new \InvalidArgumentException(
                "Requisition #{$requisition->requisition_number} cannot be approved. Current status: {$requisition->status}"
            );
        }

        $requisition->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        $requisition->refresh();

        return $requisition;
    }

    /**
     * Reject a purchase requisition with a reason.
     *
     * @param PurchaseRequisition $requisition
     * @param User $approver
     * @param string $reason
     * @return PurchaseRequisition
     *
     * @throws \InvalidArgumentException If the requisition is not in pending_approval status.
     */
    public function rejectRequisition(PurchaseRequisition $requisition, User $approver, string $reason): PurchaseRequisition
    {
        if ($requisition->status !== 'pending_approval') {
            throw new \InvalidArgumentException(
                "Requisition #{$requisition->requisition_number} cannot be rejected. Current status: {$requisition->status}"
            );
        }

        $requisition->update([
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        $requisition->refresh();

        return $requisition;
    }

    /**
     * Create a new purchase order with its line items.
     *
     * @param array $data PO header data (po_number, vendor_id, payment_terms, shipping_address, delivery_date, notes, etc.)
     * @param array $items Array of line items [['product_id' => int, 'quantity' => int, 'unit_price' => float, 'description' => string], ...]
     * @return PurchaseOrder
     */
    public function createPurchaseOrder(array $data, array $items): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $items) {
            $purchaseOrder = PurchaseOrder::create(array_merge($data, [
                'status' => 'draft',
                'order_date' => now()->toDateString(),
                'created_by' => auth()->id(),
                'subtotal' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
            ]));

            $subtotal = 0;

            foreach ($items as $itemData) {
                $lineTotal = $itemData['quantity'] * $itemData['unit_price'];
                $subtotal += $lineTotal;

                $purchaseOrder->items()->create(array_merge($itemData, [
                    'total_price' => $lineTotal,
                ]));
            }

            $taxRate = $data['tax_rate'] ?? 0;
            $taxAmount = $subtotal * ($taxRate / 100);
            $totalAmount = $subtotal + $taxAmount;

            $purchaseOrder->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
            ]);

            $purchaseOrder->refresh();

            return $purchaseOrder;
        });
    }

    /**
     * Approve a purchase order.
     *
     * @param PurchaseOrder $po
     * @param User $approver
     * @return PurchaseOrder
     *
     * @throws \InvalidArgumentException If the PO is not in pending_approval status.
     */
    public function approvePurchaseOrder(PurchaseOrder $po, User $approver): PurchaseOrder
    {
        if ($po->status !== 'pending_approval') {
            throw new \InvalidArgumentException(
                "Purchase Order #{$po->po_number} cannot be approved. Current status: {$po->status}"
            );
        }

        $po->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        $po->refresh();

        return $po;
    }

    /**
     * Convert an approved purchase requisition into a purchase order.
     *
     * @param PurchaseRequisition $requisition
     * @param int $vendorId
     * @return PurchaseOrder
     *
     * @throws \InvalidArgumentException If the requisition is not approved.
     */
    public function convertRequisitionToPo(PurchaseRequisition $requisition, int $vendorId): PurchaseOrder
    {
        if ($requisition->status !== 'approved') {
            throw new \InvalidArgumentException(
                "Only approved requisitions can be converted to purchase orders. Current status: {$requisition->status}"
            );
        }

        return DB::transaction(function () use ($requisition, $vendorId) {
            $requisition->load('items');

            $poItems = $requisition->items->map(function (PurchaseRequisitionItem $item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'description' => $item->specifications ?? $item->description ?? '',
                ];
            })->toArray();

            $purchaseOrder = $this->createPurchaseOrder([
                'vendor_id' => $vendorId,
                'requisition_id' => $requisition->id,
                'created_by' => $requisition->requested_by,
            ], $poItems);

            $requisition->update(['status' => 'converted']);

            return $purchaseOrder;
        });
    }
}
