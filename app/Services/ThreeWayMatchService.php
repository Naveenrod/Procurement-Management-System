<?php

namespace App\Services;

use App\Models\GoodsReceiptItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PurchaseOrderItem;

class ThreeWayMatchService
{
    /**
     * Perform a three-way match for an invoice.
     *
     * Compares each invoice line item against the corresponding purchase order item
     * and goods receipt item to verify quantities and prices.
     *
     * @param Invoice $invoice
     * @return array{status: string, details: array}
     */
    public function performMatch(Invoice $invoice): array
    {
        $invoice->load([
            'items.purchaseOrderItem',
            'purchaseOrder.items',
            'purchaseOrder.goodsReceipts.items',
        ]);

        $details = [];
        $overallStatus = 'matched';

        foreach ($invoice->items as $invoiceItem) {
            $poItem = $invoiceItem->purchaseOrderItem;

            if (!$poItem) {
                $details[] = [
                    'invoice_item_id' => $invoiceItem->id,
                    'product_id' => $invoiceItem->product_id,
                    'status' => 'mismatch',
                    'reason' => 'No matching purchase order item found.',
                ];
                $overallStatus = 'mismatch';
                continue;
            }

            // Find the corresponding goods receipt item for this product
            $grItem = null;
            if ($invoice->purchaseOrder && $invoice->purchaseOrder->goodsReceipts) {
                foreach ($invoice->purchaseOrder->goodsReceipts as $goodsReceipt) {
                    $grItem = $goodsReceipt->items
                        ->where('product_id', $invoiceItem->product_id)
                        ->first();

                    if ($grItem) {
                        break;
                    }
                }
            }

            $lineResult = $this->compareLineItems($invoiceItem, $poItem, $grItem);
            $details[] = $lineResult;

            if ($lineResult['status'] === 'mismatch') {
                $overallStatus = 'mismatch';
            }
        }

        return [
            'status' => $overallStatus,
            'details' => $details,
        ];
    }

    /**
     * Compare an individual invoice line item against PO and goods receipt items.
     *
     * Rules:
     * - Invoice quantity must be <= received quantity (from goods receipt)
     * - Invoice unit price must be <= PO unit price
     *
     * @param InvoiceItem $invoiceItem
     * @param PurchaseOrderItem $poItem
     * @param GoodsReceiptItem|null $grItem
     * @return array{invoice_item_id: int, product_id: int, status: string, reasons: array, po_quantity: int, po_unit_price: float, invoice_quantity: int, invoice_unit_price: float, received_quantity: int|null}
     */
    public function compareLineItems(
        InvoiceItem $invoiceItem,
        PurchaseOrderItem $poItem,
        ?GoodsReceiptItem $grItem
    ): array {
        $reasons = [];
        $status = 'matched';

        $receivedQuantity = $grItem ? $grItem->quantity_received : null;

        // Check quantity: invoice qty should not exceed received qty
        if ($grItem === null) {
            $reasons[] = 'No goods receipt found for this item.';
            $status = 'mismatch';
        } elseif ($invoiceItem->quantity > $grItem->quantity_received) {
            $reasons[] = sprintf(
                'Invoice quantity (%d) exceeds received quantity (%d).',
                $invoiceItem->quantity,
                $grItem->quantity_received
            );
            $status = 'mismatch';
        }

        // Check price: invoice unit price should not exceed PO unit price
        if ($invoiceItem->unit_price > $poItem->unit_price) {
            $reasons[] = sprintf(
                'Invoice unit price (%.2f) exceeds PO unit price (%.2f).',
                $invoiceItem->unit_price,
                $poItem->unit_price
            );
            $status = 'mismatch';
        }

        return [
            'invoice_item_id' => $invoiceItem->id,
            'product_id' => $invoiceItem->product_id,
            'status' => $status,
            'reasons' => $reasons,
            'po_quantity' => $poItem->quantity,
            'po_unit_price' => (float) $poItem->unit_price,
            'invoice_quantity' => $invoiceItem->quantity,
            'invoice_unit_price' => (float) $invoiceItem->unit_price,
            'received_quantity' => $receivedQuantity,
        ];
    }
}
