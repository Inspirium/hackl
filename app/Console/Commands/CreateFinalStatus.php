<?php

namespace App\Console\Commands;

use App\Models\Tournament;
use App\Models\TournamentRound;
use Illuminate\Console\Command;

class CreateFinalStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:final_status';

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
        $tournaments = Tournament::all();
        /** @var Tournament $tournament */
        foreach ($tournaments as $tournament) {
            $no_rounds = $tournament->rounds->count();
            /** @var TournamentRound $round */
            foreach ($tournament->rounds as $round) {
                foreach ($round->games as $game) {
                    $loser = null;
                    $this->line($game->id);
                    if (in_array($game->is_surrendered, [1, 2]) && $game->players->count()) {
                        $l = $game->is_surrendered - 1;
                        $winner = $game->players[! $l]??$game->players[$l];
                        $loser = $game->players[$l]??null;
                    } else {
                        if ($game->result) {
                            // determine winner
                            $winner = $game->result->players[$game->result->winner];
                            $loser = $game->result->players[!$game->result->winner];
                        }
                    }
                    if ($loser) {
                        $tournament->players()->updateExistingPivot($loser->id, ['final_status' => $round->order . '/' . $no_rounds]);
                    }
                    if ($round->order == $no_rounds) {
                        $tournament->players()->updateExistingPivot($winner->id, ['final_status' => '*']);
                    }
                }
            }
        }
    }
}
