<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseOrderStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private readonly PurchaseOrder $purchaseOrder
    ) {}

    public function via(): array
    {
        return ['database'];
    }

    public function toArray(): array
    {
        $status = $this->purchaseOrder->status?->value ?? 'updated';

        return [
            'message' => "Purchase Order {$this->purchaseOrder->po_number} has been {$status}.",
            'url' => route('procurement.purchase-orders.show', $this->purchaseOrder),
            'purchase_order_id' => $this->purchaseOrder->id,
            'status' => $this->purchaseOrder->status?->value,
        ];
    }
}
