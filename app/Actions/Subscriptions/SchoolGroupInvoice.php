<?php

namespace App\Actions\Subscriptions;

use App\Models\SchoolGroup;
use App\Models\Subscription;

class SchoolGroupInvoice
{
    public function __construct()
    {
        //
    }

    public function execute($request, SchoolGroup $group, $subscription)
    {
        // get all active subscriptions for this school group
        $user_subscriptions = $group->subscriptions()->where('subscription_id', $subscription)->where('is_paused', 0)->get();
        foreach ($user_subscriptions as $subscription) {
            $subscription->invoices()->create([
                'amount' => $subscription->subscription->price,
                'total_amount' => $subscription->subscription->price,
                'payment_method' => 'CASH',
                'invoice_number' => 'CASH',
                'invoice_date' => now(),
                'invoice_due_date' => now(),
                'invoice_status' => 'PAID',
                'invoice_type' => 'SUBSCRIPTION',
                'invoiceable_id' => $group->id,
                'invoiceable_type' => SchoolGroup::class,
                'user_id' => $group->trainer_id,
                'club_id' => $group->club_id,
            ]);
        }
    }
}
