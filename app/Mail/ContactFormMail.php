<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $data
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->data['message_type']) {
            'prayer' => 'Prayer Request from '.$this->data['name'],
            'partnership' => 'Partnership Inquiry from '.$this->data['name'],
            'support' => 'Support Request from '.$this->data['name'],
            'resources' => 'Resource Request from '.$this->data['name'],
            default => 'Contact Form Message from '.$this->data['name'],
        };

        return new Envelope(
            subject: $subject,
            replyTo: [$this->data['email']],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-form',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
