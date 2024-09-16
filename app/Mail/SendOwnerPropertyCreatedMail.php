<?php

namespace App\Mail;

use App\Models\Property;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOwnerPropertyCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public Property $property) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.owner-property-created',
            with: [
                'user' => $this->user,
                'property' => $this->property
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
