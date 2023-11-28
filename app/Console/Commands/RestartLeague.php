<?php

namespace App\Console\Commands;

use App\Actions\CreateGame;
use App\Models\League;
use Illuminate\Console\Command;

class RestartLeague extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:league:restart';

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
        $league = League::find(313);
        // delete old games
        foreach ($league->groups as $group) {
            foreach ($group->games as $game) {
                $game->forceDelete();
            }
        }

        foreach ($league->groups as $group) {
            for ($round = 1; $round <= $league->rounds_of_play; $round++) {
                for ($i = 0; $i < $group->players_in_group - 1; $i++) {
                    for ($j = $i + 1; $j < $group->players_in_group; $j++) {
                        if ($round % 2 === 1) {
                            CreateGame::create($group, [$group->players[$i], $group->players[$j]]);
                        } else {
                            CreateGame::create($group, [$group->players[$j], $group->players[$i]]);
                        }
                    }
                }
            }
        }
    }
}
