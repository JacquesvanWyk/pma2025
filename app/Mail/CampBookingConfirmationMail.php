<?php

namespace App\Mail;

use App\Models\CampBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampBookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CampBooking $booking,
        public string $eftReference,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Camp Meeting 2026 — Booking Confirmation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.camp-booking-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
