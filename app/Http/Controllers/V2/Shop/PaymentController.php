<?php

namespace App\Http\Controllers\V2\Shop;

use App\Actions\Wallet\WalletTransaction;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Reservation $reservation)
    {
        $club = $request->get('club');
        $walletTransaction = new WalletTransaction();
        $user = $request->input('buyer');
        if ($user) {
            $user = User::find($user['id']);
        } else {
            $user = Auth::user();
        }
        if ($request->has('wallet')) {
            $wallet = Wallet::find($request->input('wallet')['id']);
        } else {
            $wallet = $walletTransaction->getWallet($user, $club);
        }
        if (!$wallet) {
            throw new \Exception('No wallet');
            // return response()->json(['no wallet'], 404);
        }
        if (!Auth::user()->is_admin && $wallet->owner_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
            // return response()->json(['unauthorized'], 403);
        }
        $cash = $request->input('cash');
        $note = $request->get('note');
        $all = $request->input('for_all');
        $data = [
            'payment_fiskal' => $request->input('payment_fiskal'),
            'note' => $note,
            'cash' => $cash
        ];

        if ($cash) {
            // add funds to wallet
            if ($all) {
                $total = collect($reservation->getPrices())->sum();
            } else {
                $total = $reservation->getPrices()[$user->id];
            }
            $walletTransaction->handle($wallet, $user, $total, 'Plaćanje rezervacije u gotovini');
        }
        if ($all) {
            foreach ($reservation->players as $team) {
                foreach ($team->players as $player) {
                    $this->singleUserTransaction($reservation, $player, $wallet, $club, $note, $data);
                }
            }
        } else {
            $this->singleUserTransaction($reservation, $user, $wallet, $club, $note, $data);
        }

        // check if all payments are in and set
        if ($all) {
            $reservation->payment_date = Carbon::now();
            $reservation->save();
        } else {
            $payment_count = $reservation->payments()->count();
            $player_count = 0;
            foreach ($reservation->players as $team) {
                $player_count += $team->number_of_players;
            }
            if ($payment_count === $player_count) {
                $reservation->payment_date = Carbon::now();
                $reservation->save();
            }
        }


        return response()->json(['success']);
    }

    private function singleUserTransaction($reservation, $user, $wallet, $club = null, $note = '', $data = []) {
        if (!$club) {
            $club = \request()->get('club')->id;
        }
        $price = $reservation->getPrices()[$user->id];
        $payment = new Payment([
            'club_id' => $club->id,
            'user_id' => $user->id,
            'receiver_id' => 2,
            'amount' => $price,
            'wallet_id' => $wallet->id,
            'data' => $data
        ]);
        $payment->save();
        $payment->type()->associate($reservation);
        $payment->save();

        // wallet transaction
        if (!$note) {
            $note = 'Plaćanje rezervacije';
        }
        $walletTransaction = new WalletTransaction();
        $walletTransaction->handle($wallet, $user, -$price, $note);
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
