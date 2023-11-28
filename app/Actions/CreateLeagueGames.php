<?php

namespace App\Actions;

use App\Models\Game;
use App\Models\League;

class CreateLeagueGames
{
    public function handle(League $league)
    {
        // TODO: optimize this
        foreach ($league->groups as $group) {
            for ($round = 1; $round <= $group->league->rounds_of_play; $round++) {
                $players = $group->players()->whereNull('freeze')->get();
                $no = $players->count();
                $home = [];
                for ($i = 0; $i < $no - 1; $i++) {
                    for ($j = $i + 1; $j < $no; $j++) {
                        $game = Game::query()
                            ->where('type_id', $group->id)
                            ->where('round_of_play', $round)
                            ->whereHas('players', function ($q) use ($i, $players) {
                                $q->where('teams.id', $players[$i]->id);
                            })
                            ->whereHas('players', function ($q) use ($j, $players) {
                                $q->where('teams.id', $players[$j]->id);
                            })->first();
                        if ($game) {
                            continue;
                        }
                        if (!$game) {
                            if ($round % 2) {
                                if ($pos = array_search($players[$i]->id, $home ) !== false) {
                                    $game = CreateGame::create($group, [$players[$j], $players[$i]], null, 0, $round);
                                    unset($home[$pos]);
                                    $home[] = $players[$j]->id;
                                } else {
                                    $game = CreateGame::create($group, [$players[$i], $players[$j]], null, 0, $round);
                                    $home[] = $players[$i]->id;
                                }
                            } else {
                                if ($pos = array_search($players[$j]->id, $home ) !== false) {
                                    $game = CreateGame::create($group, [$players[$i], $players[$j]], null, 0, $round);
                                    unset($home[$pos]);
                                    $home[] = $players[$i]->id;
                                } else {
                                    $game = CreateGame::create($group, [$players[$j], $players[$i]], null, 0, $round);
                                    $home[] = $players[$j]->id;
                                }

                            }
                        }
                    }
                }
            }
        }
    }
}
