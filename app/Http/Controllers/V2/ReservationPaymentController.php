<?php

namespace App\Http\Controllers\V2;

use App\Actions\CreateTransaction;
use App\Actions\Wallet\WalletTransaction;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentIntent;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationPaymentController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Reservation $reservation)
    {
        $user = $request->input('player');
        if (!$user) {
            $user = Auth::user();
        } else {
            $user = User::find($user['id']);
        }
        $all = $request->input('for_all');
        if ($all) {
            $total = collect($reservation->getPrices())->sum();
        } else {
            $total = $reservation->getPrices()[$user->id];
        }
        $createTransaction = new CreateTransaction();
        try {
            $payment = $createTransaction->handle($request, $reservation, $total);
        } catch (\Exception $e) {
            return response()->json(['error', 400, $e->getMessage()]);
        }

        if ($request->input('payment_method') === 'card' && $reservation->court->club->payment_online) {
            // create payment intent and return client secret
            $key = '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';
            $stripe = new \Stripe\StripeClient($key);
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $total * 100,
                'currency' => 'eur', // TODO club currency
                'metadata' => [
                    'reservation_id' => $reservation->id,
                    'payment_id' => $payment->id,
                    'club_id' => $reservation->court->club->id,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                //'payment_method_types' => ['card', 'link'],
                //'setup_future_usage' => 'on_session',
                'statement_descriptor' => __('Tennis.plus invoice'),
            ]);
            $p = PaymentIntent::create([
                'intent_id' => $paymentIntent->id,
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $total,
                'currency' => 'eur',
                'status' => $paymentIntent->status,
            ]);
            $p->paymentable()->associate($payment);
            $p->save();
            return response()->json([
                'total' => $total,
                'client_secret' => $paymentIntent->client_secret,
            ]);
        }
        return response()->json(['success']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation, Payment $payment)
    {

        // reset wallet
        if (!isset($payment->data['cash']) || !$payment->data['cash']) {
            $walletTransaction = new WalletTransaction();
            $walletTransaction->handle($payment->wallet, $payment->user, $payment->amount, 'Storno');
        }
        // delete payment
        $payment->delete();
        // unset is_paid for reservation
        $reservation->payment_date = null;
        $reservation->save();

        return response()->noContent();
    }
}
