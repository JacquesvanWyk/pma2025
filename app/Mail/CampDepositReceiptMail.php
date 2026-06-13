<?php

namespace App\Mail;

use App\Models\CampBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampDepositReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CampBooking $booking,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Deposit Received — Camp Meeting 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.camp-deposit-receipt',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
