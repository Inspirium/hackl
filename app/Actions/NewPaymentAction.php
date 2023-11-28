<?php

namespace App\Actions;

use App\Models\Club;
use App\Models\Payment;
use App\Models\User;

class NewPaymentAction
{
    public function execute(Club $club, User $player, User $receiver, $type = null)
    {
        $payment = new Payment([
            'club_id' => $club->id,
            'user_id' => $player->id,
            'receiver_id' => $receiver->id,
        ]);
        $payment->save();
        if ($type) {
            $payment->amount = $type->price;
            $payment->type()->associate($type);
        } else {
            $payment->amount = request()->input('price');
        }
        $payment->save();

        return $payment;
    }
}
