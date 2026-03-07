<?php

namespace App\Notifications;

use App\Models\PurchaseRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionCreated extends Notification
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
        $requester = $this->requisition->requester?->name ?? 'A user';

        return (new MailMessage)
            ->subject("Approval Required: Requisition {$this->requisition->requisition_number}")
            ->greeting("Hello {$notifiable->name},")
            ->line("{$requester} has submitted a new purchase requisition that requires your approval.")
            ->line("**Requisition:** {$this->requisition->requisition_number}")
            ->line("**Title:** {$this->requisition->title}")
            ->line("**Department:** {$this->requisition->department}")
            ->action('Review Requisition', route('procurement.requisitions.show', $this->requisition))
            ->line('Thank you for using ProcureMS.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "New requisition {$this->requisition->requisition_number} submitted by {$this->requisition->requester?->name} requires your approval.",
            'url' => route('procurement.requisitions.show', $this->requisition),
            'requisition_id' => $this->requisition->id,
            'status' => $this->requisition->status?->value,
        ];
    }
}
