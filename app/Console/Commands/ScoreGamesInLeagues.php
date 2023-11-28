<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScoreGamesInLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:score-games-in-leagues';

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
        $leagues = \App\Models\League::query()->where('status', 4)->get();
        foreach ($leagues as $league) {
            $this->line('Scoring league ' . $league->id);
            $this->call('tennis:fix:leagues', ['num' => $league->id]);

        }
    }
}
