<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle() {
        $payload = request()->all();
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                $payload
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;
            // ... handle other event types
            default:
                // Unexpected event type
                http_response_code(400);
                exit();
        }

        http_response_code(200);
    }
}
