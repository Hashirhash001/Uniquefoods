<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries   = 3;   // retry up to 3 times on failure
    public int $timeout = 30;  // 30s max per attempt
    public int $backoff = 60;  // wait 60s between retries

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        // ── Build BCC list ──────────────────────────────────────────
        $bcc = [];

        // Always notify company inbox
        $companyEmail = config('mail.order_notification_email');
        if ($companyEmail) {
            $bcc[] = new Address($companyEmail, 'Unique Foods Orders');
        }

        // Dev email — only in local/staging, never in production
        if (! app()->environment('production')) {
            $bcc[] = new Address('hashmvhashmuhammed007@gmail.com', 'Dev Notifications');
        }

        return new Envelope(
            subject: 'Order Confirmed – #' . $this->order->order_number . ' | Unique Foods',
            bcc:     $bcc,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }
}
