<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseOrderStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private readonly PurchaseOrder $purchaseOrder
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
        $status = $this->purchaseOrder->status?->value ?? 'updated';

        return (new MailMessage)
            ->subject("Purchase Order {$this->purchaseOrder->po_number} {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Purchase order **{$this->purchaseOrder->po_number}** has been **{$status}**.")
            ->line("**Vendor:** {$this->purchaseOrder->vendor?->name}")
            ->line("**Total:** $" . number_format($this->purchaseOrder->total_amount, 2))
            ->action('View Purchase Order', route('procurement.purchase-orders.show', $this->purchaseOrder))
            ->line('Thank you for using ProcureMS.');
    }

    public function toArray(object $notifiable): array
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
