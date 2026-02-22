<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoiceStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Invoice $invoice
    ) {}

    public function via(): array
    {
        return ['database'];
    }

    public function toArray(): array
    {
        $status = $this->invoice->status?->value ?? 'updated';

        return [
            'message' => "Invoice {$this->invoice->invoice_number} has been {$status}.",
            'url' => route('procurement.invoices.show', $this->invoice),
            'invoice_id' => $this->invoice->id,
            'status' => $this->invoice->status?->value,
        ];
    }
}
