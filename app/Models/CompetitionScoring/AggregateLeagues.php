<?php

namespace App\Models\CompetitionScoring;

use App\Models\Competition;
use App\Models\Game;

class AggregateLeagues {

    protected $levels = [
        1 => [1000, 333],
        2 => [667, 222],
        3 => [445, 148],
        4 => [297, 99],
        5 => [198, 66],
        6 => [132, 44],
        7 => [88, 29],
        8 => [59, 20],
        9 => [39, 13],
        10 => [26, 9],
    ];

    public function handle(Game $game, Competition $competition, $level) {
        $winner = $game->getWinner();
        $loser = null;
        if (!$game->is_surrendered) {
            $loser = $game->getLoser();
        }
        if ($winner) {
            $w = $competition->teams()->find($winner->id);
            if (!$w) {
                $competition->teams()->attach($winner->id, ['points' => $this->levels[$level][0], 'played' => 1, 'won' => 1]);
            } else {
                $competition->teams()->updateExistingPivot($winner->id, [
                    'points' => intval($w->pivot->points) + $this->levels[$level][0],
                    'played' => $w->pivot->played++,
                    'won' => $w->pivot->won++
                ]);
            }
        }
        if ($loser) {
            $l = $competition->teams()->find($loser->id);
            if (!$l) {
                $competition->teams()->attach($loser->id, [ 'points' => $this->levels[$level][1], 'played' => 1, 'lost' => 1]);
            } else {
                $competition->teams()->updateExistingPivot($loser->id, [
                    'points' => intval($l->pivot->points) + $this->levels[$level][1],
                    'played' =>  $l->pivot->played++,
                    'lost' => $l->pivot->lost++
                ]);
            }
        }
    }
}
