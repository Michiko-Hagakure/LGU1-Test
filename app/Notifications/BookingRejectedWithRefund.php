<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejectedWithRefund extends Notification
{
    public $booking;
    public $reason;
    public $refund;

    public function __construct($booking, $reason, $refund)
    {
        $this->booking = $booking;
        $this->reason = $reason;
        $this->refund = $refund;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Booking Rejected - Refund of ₱' . number_format($this->refund->refund_amount, 2))
                    ->markdown('emails.booking-rejected-refund', [
                        'booking' => $this->booking,
                        'reason' => $this->reason,
                        'refund' => $this->refund,
                    ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'facility_name' => $this->booking->facility_name ?? 'N/A',
            'booking_reference' => $this->booking->booking_reference ?? 'N/A',
            'reason' => $this->reason,
            'refund_amount' => $this->refund->refund_amount,
            'refund_id' => $this->refund->id,
            'message' => 'Your booking has been rejected. A refund of ₱' . number_format($this->refund->refund_amount, 2) . ' will be processed. Please choose your refund method.',
        ];
    }
}
