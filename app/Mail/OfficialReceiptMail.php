<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfficialReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Public properties are automatically available in the Blade view.
     * We pass the transaction record and citizen profile data.
     */
    public $transaction;
    public $citizen;

    /**
     * Create a new message instance.
     * Injection of transaction and citizen data from TransactionController.
     */
    public function __construct($transaction, $citizen)
    {
        $this->transaction = $transaction;
        $this->citizen = $citizen;
    }

    /**
     * Get the message envelope (Email Metadata).
     * Defines the subject line seen by the citizen in their inbox.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Official Digital Receipt - LGU1 Facilities',
        );
    }

    /**
     * Get the message content definition (Email Template).
     * This links to resources/views/emails/receipt.blade.php.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.receipt', // Fixed path to match your folder structure
        );
    }

    /**
     * Get the attachments for the message.
     * Can be used to attach PDF versions of the receipt in the future.
     */
    public function attachments(): array
    {
        return [];
    }
}