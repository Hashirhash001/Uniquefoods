<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class OrderUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order ' . $this->order->order_number . ' Has Been Updated',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.updated',
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('admin.orders.invoice-pdf', [
            'order'    => $this->order,
            'logoData' => null,
        ])->setPaper('a4', 'portrait');

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                'invoice-' . $this->order->order_number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
