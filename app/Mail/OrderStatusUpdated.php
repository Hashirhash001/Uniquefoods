<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderStatusUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60; // increased — PDF generation takes time
    public int $backoff = 60;

    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus,
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'shipped'   => 'Your Order #' . $this->order->order_number . ' Has Been Shipped! 🚚',
            'delivered' => 'Your Order #' . $this->order->order_number . ' Has Been Delivered! ✅',
            'cancelled' => 'Your Order #' . $this->order->order_number . ' Has Been Cancelled',
        ];

        $bcc = [];
        $companyEmail = config('mail.order_notification_email');
        if ($companyEmail) {
            $bcc[] = new Address($companyEmail, 'Unique Foods Orders');
        }

        return new Envelope(
            subject: $subjects[$this->newStatus] ?? 'Your Order #' . $this->order->order_number . ' Status Updated',
            bcc:     $bcc,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-updated',
        );
    }

    public function attachments(): array
    {
        $order = $this->order;

        $pdf = Pdf::loadView('admin.orders.invoice-pdf', compact('order'))
                ->setPaper('a4', 'portrait');

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'invoice-' . $this->order->order_number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }

}
