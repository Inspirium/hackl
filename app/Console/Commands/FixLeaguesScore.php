<?php

namespace App\Console\Commands;

use App\Actions\RecalculateLeagueELO;
use App\Models\League;
use Illuminate\Console\Command;

class FixLeaguesScore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:fix:leagues {num}';

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
    public function handle(RecalculateLeagueELO $recalculateLeagueELO)
    {
        $num = $this->argument('num');
        //get active leagues
        //$leagues = League::query()->where('status', 4)->get();
        //foreach ($leagues as $league) {
        $league = League::find($num);
        if ($league) {
            $recalculateLeagueELO->handle($league);
        }
        //}
    }
}
