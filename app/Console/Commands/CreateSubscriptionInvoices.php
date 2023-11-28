<?php

namespace App\Console\Commands;

use App\Actions\Subscriptions\CreateInvoice;
use App\Models\SchoolGroup;
use App\Notifications\SchoolGroupInvoicesCreated;
use Illuminate\Console\Command;

class CreateSubscriptionInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-subscription-invoices {date}';

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
        $c = new CreateInvoice();
        $date = $this->argument('date');
        $schoolGroups = SchoolGroup::whereHas('subscriptions', function ($query) use ($date) {
            $query->whereHas('subscription', function($query) use ($date) {
                $query->where('sending_bills', $date)
                ->where('status', 'active');
            });
        })->get();
        foreach ($schoolGroups as $schoolGroup) {
            $this->line('Creating invoice for school group: ' . $schoolGroup->name);
            $c->execute($schoolGroup);
            foreach ($schoolGroup->trainers as $trainer) {
                $trainer->notify(new SchoolGroupInvoicesCreated($schoolGroup));
            }
        }

    }
}
