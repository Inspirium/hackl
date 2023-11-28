<?php

namespace App\Console\Commands;

use App\Models\Club;
use Illuminate\Console\Command;

class DeleteReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:delete-reservations';

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
        $club = Club::find(6);
        foreach ($club->courts as $court) {
            $reservations = $court->reservations()->whereDate('from', '>=', '2023-04-03')->get();
            foreach ($reservations as $reservation) {
                $this->line($reservation->id . ' ' . $reservation->from->format('Y-m-d'));
                $reservation->delete();
            }
        }
    }
}
