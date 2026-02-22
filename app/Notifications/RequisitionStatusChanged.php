<?php

namespace App\Notifications;

use App\Models\PurchaseRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequisitionStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private readonly PurchaseRequisition $requisition
    ) {}

    public function via(): array
    {
        return ['database'];
    }

    public function toArray(): array
    {
        $status = $this->requisition->status?->value ?? 'updated';

        return [
            'message' => "Requisition {$this->requisition->requisition_number} has been {$status}.",
            'url' => route('procurement.requisitions.show', $this->requisition),
            'requisition_id' => $this->requisition->id,
            'status' => $this->requisition->status?->value,
        ];
    }
}
