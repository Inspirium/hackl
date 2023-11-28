<?php

namespace App\Actions;

use App\Actions\Wallet\WalletTransaction;
use App\Mail\SendInvoice;
use App\Models\Payment;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateTransaction
{
    /**
     * @throws \Exception
     */
    public function handle(Request $request, $object, $total) {
        $method = $request->input('payment_method');
        $club = $object->club ?? $object->court->club;
        $walletTransaction = new WalletTransaction();
        $user = $request->input('buyer');
        if ($user) {
            $user = User::find($user['id']);
        } else {
            $user = Auth::user();
        }
        if ($method === 'card' || $method === 'cash') {
            $wallet = new Wallet();
        }
        else if ($request->has('wallet')) {
            $wallet = Wallet::find($request->input('wallet')['id']);
        } else {
            $wallet = $walletTransaction->getWallet($user, $club);
        }
        if (!$wallet) {
            throw new \Exception('No wallet');
        }
        if (!Auth::user()->is_admin && $wallet->owner_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }
        $cash = $request->input('cash');
        $note = $request->get('note');
        $all = $request->input('for_all');
        $receipt = $request->input('receipt');
        $data = [
            'payment_fiskal' => $request->input('payment_fiskal'),
            'note' => $note,
            'cash' => $cash,
            'payment_method' => $request->input('payment_method', ''),
            'all' => $all,
            'company' => $request->input('company'),
        ];

        /*if ($cash) {
            // add funds to wallet
            $walletTransaction->handle($wallet, $user, $total, 'Plaćanje u gotovini');
        }*/
        $payment = $this->singleUserTransaction($object, $total, $user, $wallet, $club, $note, $data);
        if ($cash && $receipt) {
            // send receipt
            $c = new CreateInvoice();
            $invoice = $c->handle($user, $payment, [$payment]);
            //send invoice
            Mail::to([$user->email])->send(new SendInvoice($user, $invoice));
        }
        return $payment;
    }

    private function singleUserTransaction($object, $price, $user, $wallet, $club = null, $note = '', $data = []) {
        if (!$club) {
            $club = \request()->get('club')->id;
        }
        $receiver = Auth::id();
        $payment = new Payment([
            'club_id' => $club->id,
            'user_id' => $user->id,
            'receiver_id' => $receiver,
            'amount' => $price,
            'wallet_id' => $wallet->id,
            'data' => $data,
        ]);
        $payment->save();
        $payment->type()->associate($object);
        if ($data['cash']) {
            $payment->paid_at = Carbon::now();
        }
        $payment->save();
        if ($wallet->id) {
            $payment->paid_at = Carbon::now();
            $payment->save();
            // wallet transaction
            if (!$note) {
                $note = 'Plaćanje rezervacije';
            }
            $walletTransaction = new WalletTransaction();
            $p2 = $walletTransaction->handle($wallet, $user, -$price, $note);
            if ($club->id == 4) {
                $c = new CreateInvoice();
                $c->handle($user, $payment, [$payment, $p2]);
            }
        }

        return $payment;
    }

    public function addFunds(Request $request) {
        $user = Auth::user();
        $club = $request->get('club');
        $wallet = $user->wallets()->where('club_id', $club->id)->first();
        $data = [
            'payment_fiskal' => $request->input('payment_fiskal'),
            'note' => $request->input('note'),
            'cash' => false,
            'payment_method' => 'card',
        ];
        $payment = new Payment([
            'club_id' => $club->id,
            'user_id' => $user->id,
            'receiver_id' => $user->id,
            'amount' => $request->input('amount'),
            'wallet_id' => $wallet->id,
            'data' => $data,
        ]);
        $payment->save();
        $payment->type()->associate($wallet);
        $payment->save();
        return $payment;
    }
}
