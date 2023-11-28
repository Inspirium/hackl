<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentInfoSubscription extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;
    public $userSubscription;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Subscription $subscription, UserSubscription $userSubscription, User $user)
    {
        $this->subscription = $subscription;
        $this->userSubscription = $userSubscription;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('info@tennis.plus', 'Tennis.plus'),
            subject: 'Informacije za plaćanje',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $price = sprintf('%015d', $this->subscription->price*100);
        $text = <<<HUB
HRVHUB30
EUR
{$price}
{$this->user->display_name}
{$this->user->address}
{$this->user->postal_code} {$this->user->city}
Inspirium d.o.o.
Zdravka Lozančića 4
10290 Zaprešić
HR9623600001102445837
HR01
104-2021-00000010
COST
Plaćanje pretplate
HUB;
        $barcode = \Milon\Barcode\Facades\DNS2DFacade::getBarcodePNG($text, 'PDF417,,4');
        $invoice = new Invoice([
            'number' => '104-2021-00000010',
            'date' => '2021-01-04',
            'amount' => $this->subscription->price,
            'currency' => 'EUR',
            'iban' => 'HR9623600001102445837',
            'swift' => 'ZABAHR2X',
            'reference' => 'HR01',
            'description' => 'Plaćanje pretplate',
            'recipient' => 'Inspirium d.o.o.',
            'address' => 'Zdravka Lozančića 4',
            'postal_code' => '10290',
            'city' => 'Zaprešić',
            'country' => 'HR',
            'vat_number' => 'HR9623600001102445837',
            'vat' => 0,
            'user_id' => $this->user->id,

        ]);
        return new Content(
            markdown: 'emails.payment.subscription',
            with: [
                'barcode' => $barcode,
                'club' => $this->subscription->club,
                'invoice' => $invoice
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
        return [];
    }
}
