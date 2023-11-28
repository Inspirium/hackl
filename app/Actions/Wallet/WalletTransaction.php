<?php

namespace App\Actions\Wallet;

use App\Models\Club;
use App\Models\User;
use App\Models\Wallet;

class WalletTransaction
{
    public function handle(Wallet $wallet = null, User $player = null, $amount = 0, $note = '', Club $club = null) {
        if (!$amount) {
            return null;
        }
        if (!$wallet && !$player) {
            return null;
        }
        if (!$wallet && $player) {
            if (!$club) {
                $club = request()->get('club');
            }
            // get wallet
            $wallet = $this->getWallet($player, $club);
        }

        $transaction = \App\Models\WalletTransaction::create([
            'amount' => $amount,
            'user_id' => $player ? $player->id : null,
            'note' => $note,
            'wallet_id' => $wallet->id,
        ]);
        return $transaction;
    }

    public function getWallet($player, $club) {
        $wallet = Wallet::query()->where('club_id', $club->id)->where('owner_id', $player->id)->first();
        if (!$wallet) {
            // create wallet
            $wallet = Wallet::create([
                'owner_id' => $player->id,
                'club_id' => $club->id,
                'name' => 'Račun u ' . $club->name,
                'amount' => 0,
            ]);
        }

        return $wallet;
    }
}
