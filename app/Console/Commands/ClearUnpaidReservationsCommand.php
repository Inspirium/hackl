<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Notifications\ReservationCanceledPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class ClearUnpaidReservationsCommand extends Command
{
    protected $signature = 'clear:unpaid-reservations';

    protected $description = 'Delete unpaid reservations in clubs that require payment';

    public function handle(): void
    {
        $reservations = Reservation::query()->whereNull('payment_date')
            ->with('players')
            ->where('needs_payment', true)
            ->where('created_at', '<', now()->subMinutes(10)->toDateTimeString())
            ->get();
        foreach ($reservations as $reservation) {
            $this->line($reservation->created_at->toDateTimeString());
        }
        foreach ($reservations as $reservation) {
            $this->line('reservation:' . $reservation->id);
                $players = $reservation->players;
                foreach ($players as $player) {
                    foreach ($player->players as $p) {
                        $p->notify((new ReservationCanceledPayment($reservation, $p))->locale($p->lang));
                        //$out[] = $p;
                    }
                }

                //\Notification::send($out, new ReservationCanceledPayment($reservation, $out[0]));
            $reservation->delete();
        }
    }
}
