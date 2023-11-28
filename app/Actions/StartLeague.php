<?php

namespace App\Actions;

use App\Models\League;

class StartLeague
{
    public static function execute(League $league)
    {
        if ($league->classification === 'elo') {
            return self::startElo($league);
        }
        // create games in each group
        $league->load(['groups']);
        $createLeagueGames = new CreateLeagueGames();
        $createLeagueGames->handle($league);
        return true;
    }

    private static function startElo(League $league)
    {
        $league->load(['groups']);
        $ids = collect($league->players)->mapWithKeys(function ($item) {
            return [$item->id => ['player' => 1, 'score' => 1500]];
        });
        $league->groups[0]->players()->sync($ids);

        return true;
    }

    public static function fixElo(League $league)
    {
        $ids = collect($league->players)->mapWithKeys(function ($item) {
            return [$item->id => ['player' => 1, 'score' => 1500]];
        });
        $league->groups[0]->players()->sync($ids);
    }
}
