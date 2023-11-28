<?php

namespace App\Actions;

use App\Models\Court;
use App\Models\Game;
use App\Models\Team;

class CreateGame
{
    public static function create($type, $players, Court $court = null, $order = 0, $round = 0)
    {
        $game = new Game();
        $game->type()->associate($type);
        $game->round_of_play = $round;
        if ($court) {
            $game->court()->associate($court);
        }
        $game->order = $order;
        $game->save();
        foreach ($players as $player) {
            if (is_a($player, 'App\Models\User')) {
                $team = Team::where('primary_contact_id', $player->id)->where('number_of_players', 1)->first();
                $game->teams()->attach($team);
            }
            if (is_a($player, 'App\Models\Team')) {
                $game->teams()->attach($player);
            }
        }

        return $game;
    }
}
