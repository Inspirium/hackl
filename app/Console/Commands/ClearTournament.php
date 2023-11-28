<?php

namespace App\Console\Commands;

use App\Models\Tournament;
use Illuminate\Console\Command;

class ClearTournament extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:clear:cup';

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
        $tournament = Tournament::find(65);
        foreach ($tournament->rounds as $round) {
            foreach ($round->games as $game) {
                $game->delete();
            }
            $round->delete();
        }
    }
}
