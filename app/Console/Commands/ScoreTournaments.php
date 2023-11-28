<?php

namespace App\Console\Commands;

use App\Actions\ATPScore;
use App\Models\Tournament;
use App\Models\TournamentRound;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ScoreTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:score:tournaments';

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
        $tournaments = Tournament::query()->whereNotNull('data->cup_scoring')->where('status', 5)->get();
        //$tournaments = Tournament::where('id', 239)->get();
        foreach ($tournaments as $tournament) {
            $this->line($tournament->id . ' ' . $tournament->data['cup_scoring']);
            // get last played game
            /** @var TournamentRound $round */
            $round = $tournament->rounds()->orderBy('order', 'desc')->first();
            $game = $round->games()->first();
            /*if (in_array($game->is_surrendered, [1, 2])) {
                $l = $game->is_surrendered - 1;
                $winner = $game->players[! $l];
                // $loser = $game->players[$l];
            } else {
                // determine winner
                $winner = $game->result->players[$game->result->winner];
                // $loser = $game->result->players[!$game->result->winner];
            }

            $game->type->tournament->players()->updateExistingPivot($winner->id, ['final_status' => '*']);
            continue;*/
            if (!$game->played_at) {
                continue;
            }
            foreach ($tournament->players as $team) {
                $status = $team->pivot->final_status;
                $score = ATPScore::get($status, (int)$tournament->data['cup_scoring'], $tournament->rounds()->count());

                $tournament->players()->updateExistingPivot($team->id, ['final_score' => $score, 'final_at' => $game->played_at]);
            }
        }
    }
}
