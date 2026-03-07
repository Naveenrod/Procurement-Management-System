<?php

namespace App\Notifications;

use App\Models\PurchaseRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        private readonly PurchaseRequisition $requisition
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
        $status = $this->requisition->status?->value ?? 'updated';

        return (new MailMessage)
            ->subject("Requisition {$this->requisition->requisition_number} {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your purchase requisition **{$this->requisition->requisition_number}** has been **{$status}**.")
            ->when(
                $this->requisition->notes,
                fn ($mail) => $mail->line("Notes: {$this->requisition->notes}")
            )
            ->action('View Requisition', route('procurement.requisitions.show', $this->requisition))
            ->line('Thank you for using ProcureMS.');
    }

    public function toArray(object $notifiable): array
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
