<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdateReservationSeriesCommand extends Command
{
    protected $signature = 'update:reservation-series';

    protected $description = 'Command description';

    public function handle(): void
    {
        $reservations = \App\Models\Reservation::where('series', null)->whereBetween('from', [Carbon::now(), Carbon::now()->addWeeks(3)])->with('players')->get();
        foreach ($reservations as $reservation) {
            $has = true;
            $i = 1;
            // check if has matching reservation
            while($has) {
                $matching = \App\Models\Reservation::where('from', $reservation->from->addWeeks($i))
                    ->where('to', $reservation->to->addWeeks($i))
                    ->where('court_id', $reservation->court_id)
                    ->where('series', null)
                    ->with('players')
                    ->first();
                if ($matching) {
                    if ($reservation->players->count() && $matching->players->count() && $reservation->players[0]->id !== $matching->players[0]->id) {
                        break;
                    }
                    $this->line('Found matching reservation: ' . $matching->id . ' for reservation: ' . $reservation->id);
                    if (!$reservation->series) {
                        $reservation->series = Str::uuid();
                        $reservation->save();
                    }
                    $matching->series = $reservation->series;
                    $matching->save();
                    $i++;
                } else {
                    $has = false;
                }
            }
        }
    }
}
