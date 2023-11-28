<?php

namespace App\Actions\Stripe;

use App\Actions\CreateInvoice;
use App\Actions\Wallet\WalletTransaction;
use App\Mail\SendInvoice;
use App\Models\PaymentIntent;
use App\Models\Reservation;
use App\Models\Wallet;
use Illuminate\Support\Facades\Mail;

class ChargeSucceededAction {
    public function handle($object) {
        // get payment_intent
        $payment_intent = $object->payment_intent;
        $payment_intent = PaymentIntent::where('intent_id', $payment_intent)->first();
        $payable = $payment_intent->paymentable;
        if ($payable->paid_at) {
            return;
        }
        $payable->paid_at = now();

        // check if reservation
        if ($payable->type_type === Reservation::class) {
            // check if reservation is valid
            $type = $payable->load('type');
            if (!$type) {
                // get user wallet
                $w = new WalletTransaction();
                $wallet = $w->getWallet($payable->user, $payable->club);
                $payable->type()->associate($wallet);
            }
        }
        $payable->save();
        if ($payable->type_type === Wallet::class) {
            // add funds
            $c = new WalletTransaction();
            $c->handle($payable->type, $payable->user, $payable->amount, 'Uplata na račun');
        }
        // create invoice
        $items = [$payable];
        $a = new CreateInvoice();
        $invoice = $a->handle($payable->user, $payable, $items, $object);
        //send invoice
        Mail::to([$payable->user->email])->send(new SendInvoice($payable->user, $invoice));
    }
}
