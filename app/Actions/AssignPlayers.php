<?php

namespace App\Actions;

use App\Models\League;

class AssignPlayers
{
    private static function assignThread($league): void {
        foreach ($league->groups as $group) {
            $players = [];
            $group->load('players.players');
            foreach ($group->players as $team) {
                foreach ($team->players as $player) {
                    $players[] = $player->id;
                }
            }
            $group->thread->players()->sync($players);
        }
    }
    public static function random(League $league): void
    {
        $players = $league->players()->inRandomOrder()->get();
        $start = 0;
        /** @var League\Group $group */
        foreach ($league->groups as $group) {
            $pl = $players->slice($start, $group->players_in_group);
            $ids = collect($pl->all())->mapWithKeys(function ($item) {
                return [$item->id => ['player' => 1]];
            });
            $group->players()->sync($ids);
            $start += $group->players_in_group;
        }
        self::assignThread($league);
    }

    public static function strength(League $league): void
    {
        $players = $league->players;
        $pl = [];
        foreach ($players as $player) {
            $pl[] = ['id' => $player->id, 'score' => $player->score];
        }
        usort($pl, function ($a, $b) {
            return $a['score'] <=> $b['score'];
        });
        $pl = collect($pl)->map(function ($item) {
            return $item['id'];
        });
        $start = 0;
        /** @var League\Group $group */
        foreach ($league->groups as $group) {
            $ids = $pl->slice($start, $group->players_in_group);
            $ids = collect($ids->all())->mapWithKeys(function ($item) {
                return [$item => ['player' => 1]];
            });
            $group->players()->sync($ids);
            $start += $group->players_in_group;

        }
        self::assignThread($league);
    }
}
