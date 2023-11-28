<?php

namespace App\Console\Commands;

use App\Models\WalletTransaction;
use Illuminate\Console\Command;

class MigrateAccontaions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:migrate:eur';

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
        $transactions = WalletTransaction::all();
        foreach ($transactions as $transaction) {
            $value = round($transaction->amount / 7.5345, 2);
            $transaction->amount = $value;
            $transaction->update();
        }
        return Command::SUCCESS;
    }
}
