<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Invoice $invoice
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];
        if (config('mail.default') !== 'log' && $notifiable->email) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->invoice->status?->value ?? 'updated';

        return (new MailMessage)
            ->subject("Invoice {$this->invoice->invoice_number} {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Invoice **{$this->invoice->invoice_number}** has been **{$status}**.")
            ->line("**Vendor:** {$this->invoice->vendor?->name}")
            ->line("**Amount:** $" . number_format($this->invoice->total_amount, 2))
            ->action('View Invoice', route('procurement.invoices.show', $this->invoice))
            ->line('Thank you for using ProcureMS.');
    }

    public function toArray(object $notifiable): array
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
