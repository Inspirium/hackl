<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendUnpaidInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $invoice)
    {
        $this->user = $user;
        $this->invoice = $invoice;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('info@tennis.plus', 'Tennis.plus'),
            subject: 'Račun za uplatu ' . $this->invoice->invoice_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment.unpaid_invoice',
            with: [
                'user' => $this->user,
                'invoice' => $this->invoice,
                'club' => $this->invoice->club,
                'barcode' => $this->invoice->barcode,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $this->invoice->load('items');
        $pdf = \PDF::loadView('invoices.invoice', ['invoice' => $this->invoice]);
        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
