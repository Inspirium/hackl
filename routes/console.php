<?php

use App\Actions\CreateInvoice;
use App\Exceptions\PlayerHasTooManyReservations;
use App\Mail\SendInvoice;
use App\Models\Membership;
use App\Models\Team;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('test', function () {
    app(\App\Actions\Imports\UserImportAction::class)('/Users/marko/Developer/Web/tennis-frontend/static/BulkPlayerRegistration.xlsx');
});

Artisan::command('tennis:test', function () {
    $t =  __('notifications.'.\App\Notifications\NewReservation::class.'.body', [
        'name' => 'Marko',
        'time' => '22:40',
        'court' => 'teren 1',
    ], 'en');
    $this->line($t);
    $user = \App\Models\User::find(1);
    $reservation = \App\Models\Reservation::find(1);
    $user->notify((new \App\Notifications\NewReservation($reservation, $user)));
});

Artisan::command('fix:latlon', function() {
    $clubs = \App\Models\Club::where('is_active', 1)->get();
    foreach ($clubs as $club) {
        $lat = $club->latitude;
        $lon = $club->longitude;
        $club->latitude = $lon;
        $club->longitude = $lat;
        $club->save();
    }
});

Artisan::command('test:reservation', function() {
        $team = Team::query()->where('primary_contact_id', 897)
            ->where('number_of_players', 1)
            ->first();
        //$team = Team::find(897);
    $this->line($team->id);
    $buyer = $team->primary_contact;
    if (!$buyer) { // for teams with no primary contact, mostly errors in db
        return true;
    }
    $um = UserMembership::where('user_id', $buyer->id)->whereHas('membership', function ($query) {
        $query->where('club_id', 4);
    })->first();
    $um = $um?$um->membership:null;
    if (!$um) {
        $um = Membership::where('club_id', 4)->where('basic', 1)->first();
    }
    if ($um->max_reservation_per_period) {
        /*$teams = Team::query()->whereHas('players', function() use ($buyer) {
            $this->where('player_id', $buyer->id);
        })->get();*/
        $this->line(Carbon::now());
        $reservations = $team->reservations()
            ->with('players')
            ->whereHas('court', function($query) {
                $query->where('club_id', 4);
            })
            ->whereNull('school_group_id')
            ->where('from', '>=', Carbon::now())->get();
        $sum = 0;
        $this->line($reservations->count());
        foreach ($reservations as $r) {
            if ($r->players[0]->id != $team->id) continue;
            $this->line($r->id);
            $sum += $r->to->diffInMinutes($r->from);
        }
        $this->line($sum);
        if ($sum >= $um->max_reservation_per_period_days * 60 ) {
            throw new PlayerHasTooManyReservations();
        }
    }
});

Artisan::command('tenis:check', function() {
   $clubs = \App\Models\Club::query()->get();
   foreach ($clubs as $club) {
         $basic = $club->memberships()->where('basic', 1)->first();
         if (!$basic) {
             $this->line($club->name);
         }
   }
});

Artisan::command('tenis:topspin', function() {
   $club = \App\Models\Club::find(711);
   foreach ($club->courts as $court) {
       $reservations = $court->reservations()->where('from', '>=', Carbon::createFromFormat('Y-m-d', '2023-10-01'))->orderBy('from')->get();
         foreach ($reservations as $reservation) {
             $this->line($reservation->id . ' ' . $reservation->from);
             $reservation->from = $reservation->from->subWeeks(15);
             $reservation->to = $reservation->to->subWeeks(15);
             $reservation->save();
         }
   }
});


Artisan::command('tenis:tour', function() {
    $tournament = \App\Models\Tournament::find(532);
    dump($players = $tournament->players()->join('club_team', 'teams.id', '=', 'club_team.team_id')->where('club_team.club_id', $tournament->club_id)->orderBy('club_team.rating', 'desc')->get());
});

Artisan::command('tenis:test:cancel', function() {
    $reservation = \App\Models\Reservation::find(123721);
    $id = 2245;
    $um = UserMembership::where('user_id', $id)->whereHas('membership', function ($query) {
        $query->where('club_id', 4);
    })->first();
    $active_membership = null;
    if ($um) {
        $active_membership = $um->membership;
    }
    if (! $active_membership) {
        // get club basic
        $active_membership = Membership::where('club_id', 4)->where('basic', 1)->first();
    }
    if ($active_membership) {
        $this->line($active_membership->is_reservation_cancelable);
        $this->line($active_membership->reservation_cancelable);
        if (
            !$active_membership->is_reservation_cancelable ||
            Carbon::now()->diffInHours($reservation->from) < $active_membership->reservation_cancelable
        ) {
            $this->line('not cancelable');
        }
    }
    $this->line('end');
});

Artisan::command('tenis:add_country', function() {
    $c = App\Models\Country::create([
        'name' => [
            'hr' => 'Brazil',
            'en' => 'Brazil',
            'de' => 'Brasilien',
            'fr' => 'Brésil',
            'es' => 'Brasil',
        ],
        'code' => 'BR',
        'locale' => 'pt_BR',
        'currency' => 'BRL',
        'flag' => '🇧🇷',
        'latitude' => -10,
        'longitude' => -52,
    ]);
        $c = App\Models\Country::create([
        'name' => [
            'hr' => 'Australia',
            'en' => 'Australia',
            'de' => 'Australien',
            'fr' => 'Australie',
            'es' => 'Australia',
        ],
        'code' => 'AU',
        'locale' => 'en_AU',
        'currency' => 'AUD',
        'flag' => '🇦🇺',
        'latitude' => 25.0,
        'longitude' => 133.0,
    ]
    );
    //$club = \App\Models\Club::find(42);
    //$club->country()->associate($c);
});


Artisan::command('tenis:payments', function() {
    $payments = \App\Models\Payment::query()->whereNull('paid_at')->get();
    foreach ($payments as $payment) {
        if ($payment->data['payment_method'] === 'acc' || $payment->data['payment_method'] === 'cash' || $payment->data['cash']) {
            //$payment->paid_at = $payment->created_at;
            //$payment->save();
        }
    }
});

Artisan::command('tenis:res', function () {
   $court = \App\Models\Court::find(5);
   $court->getWorkingHours('2023-07-30');
});

Artisan::command('tenis:hours', function() {
    $groups = \App\Models\League\Group::query()->where('league_id', 806)->get();
    foreach ($groups as $group) {
        $reservations = \App\Models\Reservation::query()->whereHas('game', function($query) use ($group) {
            $query->where('type_id', $group->id)->where('type_type', \App\Models\League\Group::class);
        })->get();
        foreach ($reservations as $reservation) {
            $this->line($reservation->id . ' ' . $reservation->from . ' ' . $reservation->to . ' ' . $reservation->court_id . ' ' . $reservation->court->club_id);
            $reservation->from = $reservation->from->subHour();
            $reservation->to = $reservation->to->subHour();
            $reservation->save();
        }
    }

});

Artisan::command('send:invoice', function () {
    $invoice = \App\Models\Invoice::find('9a3b8bdf-fb2b-4cc6-9ff0-1ba9856788d4');
    //dd($invoice->payment_details);
    $invoice->sendEmail();
});

Artisan::command('stripe:pi', function() {
   $client = new \Stripe\StripeClient('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');
   /*$payment_intents = \App\Models\PaymentIntent::query()->whereHas('paymentable', function($query) {
       $query->where('club_id', 67);
   })
       ->where('status', 'requires_payment_method')->get();
   foreach ($payment_intents as $payment_intent) {
       //dump($payment_intent->id);
       $pi = $client->paymentIntents->retrieve($payment_intent->intent_id);
       dump($payment_intent->id . ' ' . $pi->status);
       $payment_intent->status = $pi->status;
       $payment_intent->save();
   }*/
    $payments = \App\Models\Payment::query()->where('club_id', 67)->withTrashed()->get();
    $total = 0;
    foreach ($payments as $payment) {
        if ($payment->paymentIntent?->status === 'requires_payment_method') {
            try {
                $pi = $client->paymentIntents->retrieve($payment->paymentIntent->intent_id);
                if ($pi->status === 'succeeded') {
                    $payment->paymentIntent->status = $pi->status;

                    $payment->paymentIntent->save();

                    if ($payment->deleted_at) {
                        $payment->deleted_at = null;
                        $total += $payment->amount;
                    }
                    $payment->paid_at = Carbon::createFromTimestamp($pi->created)->toDateTimeString();
                    $payment->save();
                }
            } catch (\Stripe\Exception\ApiErrorException $e) {

            }

        }
    }
    $this->line($total);
});

Artisan::command('payments', function() {
    $intents = \App\Models\PaymentIntent::query()->where('status', 'succeeded')->whereMonth('created_at', '10')->get();
    foreach ($intents as $intent) {
        $payment = $intent->paymentable;
        $invoice_item = \App\Models\InvoiceItem::query()->where('invoiceable_type', \App\Models\Payment::class)
            ->where('invoiceable_id', $payment->id)->first();
        if (!$invoice_item) {
            $payment->load(['user', 'club']);
            dump($payment->id . ' ' . $payment->created_at . ' ' . $payment->club_id . ' ' . $payment->amount . ' ' . $intent->intent_id);
            // create invoice
            // add to wallet
                $walletTransaction = new \App\Actions\Wallet\WalletTransaction();
                if ($payment->paid_at) {
                    $reservation = $payment->type;
                    $walletTransaction->handle(null, $payment->user, $payment->amount, 'Povrat sredstava za rezervaciju', $payment->club);
                }
            $items = [$payment];
            $a = new CreateInvoice();
            $object = new \stdClass();
            $object->id = $intent->payment_id;
            $invoice = $a->handle($payment->user, $payment, $items, $object);
        }
    }
});

Artisan::command('app:reverse-reservations', function() {
    $reservation = \App\Models\Reservation::withTrashed()->find(180272);
    $walletTransaction = new \App\Actions\Wallet\WalletTransaction();
    // get payments
    foreach ($reservation->payments as $payment) {
        if ($payment->paid_at) {
            $walletTransaction->handle(null, $payment->user, $payment->amount, 'Povrat sredstava za rezervaciju: '. $reservation->from . ' ' . $reservation->court->name, $payment->club);
        }
    }
});

Artisan::command('payment-intents', function() {
    $client = new \Stripe\StripeClient('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');
    $intents = \App\Models\PaymentIntent::where('status', 'succeeded')
        ->whereNotNull('payment_id')
        ->get();
    foreach ($intents as $intent) {
        //$pi = $client->paymentIntents->retrieve($intent->intent_id);

        if ($intent->paymentable->invoice_item) {
            $client->paymentIntents->update(
                $intent->intent_id,
                ['metadata' => [
                    //'invoice_id' => $intent->paymentable->invoice_item->invoice->uuid,
                    //'invoice_number' => $intent->paymentable->invoice_item->invoice->invoice_number,
                    //'invoice_date' => $intent->paymentable->invoice_item->invoice->created_at->toDateString(),
                    'club_id' => $intent->paymentable->invoice_item->invoice->club_id,
                    ]]
            );
        }
        //$intent->payment_id = $pi->latest_charge;
        //$intent->save();
    }
});

Artisan::command('invoice-filter', function() {
    $invoices = \App\Models\Invoice::query()->where('club_id', 67)->whereBetween('created_at', [
        Carbon::parse('01-09-2023')->toDateString(), Carbon::parse('01-10-2023')->toDateString()
    ])->whereNotNull('invoice_number')->get();
    $fp = fopen('test.csv', 'w');
    foreach ($invoices as $invoice) {
        fwrite($fp, $invoice->invoice_number . "\n");
    }
    fclose($fp);
});

Artisan::command('member-invoice', function() {
   $c = new \App\Actions\Membership\CreateInvoice();
   $c->execute(\App\Models\Membership::find(42));
});
