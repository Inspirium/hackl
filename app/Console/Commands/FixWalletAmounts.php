<?php

namespace App\Console\Commands;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;

class FixWalletAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:fix:wallets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $wallets = Wallet::all();

        foreach ($wallets as $wallet) {
            $amount = 0;
            $transactions = WalletTransaction::where('wallet_id', $wallet->id)->get();
            foreach ($transactions as $transaction) {
                $amount += $transaction->amount;
            }
            $wallet->amount = $amount;
            $wallet->save();
        }
    }
}
