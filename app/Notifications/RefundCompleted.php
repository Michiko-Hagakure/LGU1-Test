<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundCompleted extends Notification
{
    public $refund;

    public function __construct($refund)
    {
        $this->refund = $refund;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Refund Completed - ₱' . number_format($this->refund->refund_amount, 2))
                    ->markdown('emails.refund-completed', [
                        'refund' => $this->refund,
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'refund_id' => $this->refund->id,
            'booking_reference' => $this->refund->booking_reference,
            'refund_amount' => $this->refund->refund_amount,
            'refund_method' => $this->refund->refund_method,
            'message' => 'Your refund of ₱' . number_format($this->refund->refund_amount, 2) . ' has been completed via ' . ucfirst(str_replace('_', ' ', $this->refund->refund_method)) . '.',
        ];
    }
}
