<?php

namespace App\Console\Commands;

use App\Mail\PaymentInfoSubscription;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Console\Command;

class SendSubscriptionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:send-subscription-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subcription = Subscription::find(12);
        $user = User::find(2);
        $userSubscription = UserSubscription::find(133);
        \Mail::to('stjepan@inspirium.hr')->send(new PaymentInfoSubscription($subcription, $userSubscription, $user));
    }
}
