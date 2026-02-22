<?php

namespace App\Notifications;

use App\Models\PurchaseRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequisitionCreated extends Notification
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
        return [
            'message' => "New requisition {$this->requisition->requisition_number} submitted by {$this->requisition->requester?->name} requires your approval.",
            'url' => route('procurement.requisitions.show', $this->requisition),
            'requisition_id' => $this->requisition->id,
            'status' => $this->requisition->status?->value,
        ];
    }
}
