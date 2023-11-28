<?php

namespace App\Mail;

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

class PaymentInfo extends Mailable
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
        return new Content(
            view: 'emails.payment.info',
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
