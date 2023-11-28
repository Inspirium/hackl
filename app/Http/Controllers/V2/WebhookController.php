<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index(Request $request) {
        $endpoint_secret = 'whsec_5kwUJEnKD4ZNqZeleLah5xWYZJepGoxE';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json([
                'message' => 'Invalid payload',
            ], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json([
                'message' => 'Invalid payload',
            ], 400);
        }


        // Handle the event
        switch ($event->type) {
            case 'charge.succeeded':
                $charge = $event->data->object;
                $action = new \App\Actions\Stripe\ChargeSucceededAction();
                $action->handle($charge);
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
                break;
        }

        return response()->json([
            'message' => 'Webhook received',
        ], 200);
    }
}
