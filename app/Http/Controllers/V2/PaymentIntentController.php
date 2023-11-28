<?php

namespace App\Http\Controllers\V2;

use App\Actions\CreateTransaction;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentIntent;
use Illuminate\Http\Request;

class PaymentIntentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $amount = $request->input('amount');
        $note = $request->input('note');
        $user = \Auth::user();

        $stripe = new \Stripe\StripeClient('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');
        $c = new CreateTransaction();
        $payment = $c->addFunds($request);
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $amount * 100,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
            'metadata' => [
                'payment_id' => $payment->id,
                'club_id' => $payment->club_id,
            ],
            'statement_descriptor' => 'Tennis.plus invoice',
        ]);
        $p = PaymentIntent::create([
            'intent_id' => $paymentIntent->id,
            'client_secret' => $paymentIntent->client_secret,
            'amount' => $amount,
            'currency' => 'eur',
            'status' => $paymentIntent->status,
        ]);
        $p->paymentable()->associate($payment);
        $p->save();

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
