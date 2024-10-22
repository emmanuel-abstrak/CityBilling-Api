<?php

namespace App\Mail;

use App\Models\WaterPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private readonly WaterPurchase $purchase) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ' | Purchase Receipt',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.purchase-receipt',
            with: [
                'purchase' => $this->purchase
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
