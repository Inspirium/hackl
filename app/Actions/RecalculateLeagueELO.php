<?php

namespace App\Actions;

use App\Models\Game;
use App\Models\League;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RecalculateLeagueELO
{
    public function handle(League $league)
    {
        $stats = new ProcessStats();
        // dump('liga ' .$league->id);
        foreach ($league->groups as $group) {
            // reset all scores in league group
            DB::table('league_group_player')->where('league_group_id', $group->id)->update(['score' => $league->classification === 'elo' ? 1500 : 0]);
            // get all played games in league group
            $games = Game::query()
                ->where('type_id', '=', $group->id)->where('type_type', '=', 'App\Models\League\Group')
                ->whereHas('result')
                ->orderBy('played_at', 'asc')
                ->get();
            // score games
            foreach ($games as $game) {
                // dump('game ' . $game->id);
                ScoreGame::score($game);
            }
            $games = Game::query()
                ->where('type_id', '=', $group->id)->where('type_type', '=', 'App\Models\League\Group')
                ->whereIn('is_surrendered', [1,2])
                ->get();
            foreach ($games as $game) {
                ScoreGame::score($game);
            }
            $stats->handle($group);
        }
    }
}
