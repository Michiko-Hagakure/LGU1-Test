<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class RefundRequestCreated extends Notification
{
    public $refund;

    public function __construct($refund)
    {
        $this->refund = $refund;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'refund_id' => $this->refund->id,
            'booking_reference' => $this->refund->booking_reference,
            'applicant_name' => $this->refund->applicant_name,
            'refund_amount' => $this->refund->refund_amount,
            'refund_type' => $this->refund->refund_type,
            'message' => "New refund request created for {$this->refund->booking_reference}. {$this->refund->applicant_name} is eligible for a ₱" . number_format($this->refund->refund_amount, 2) . " refund. Waiting for citizen to select refund method.",
        ];
    }
}
