<?php

namespace App\Actions;

use App\Http\Resources\ResultResource;
use App\Models\Result;
use Illuminate\Support\Facades\Cache;

class VSStats
{
    public function handle($player, $player2) {
        if (!$player || !$player2) {
            return null;
        }
        $out = Cache::get('vs_stats_' . $player->id . '_' . $player2->id);
        if (!$out) {
            $stats = [];
            $stats[$player->id] = [
                'wins' => 0,
                'sets' => 0,
                'points' => 0,
                'tie_breaks' => 0,
                'first_set_wins' => 0,
            ];
            $stats[$player2->id] = [
                'wins' => 0,
                'sets' => 0,
                'points' => 0,
                'tie_breaks' => 0,
                'first_set_wins' => 0,
            ];
            $results = $player->results()->whereHas('players', function ($query) use ($player2) {
                $query->where('participant_id', $player2->id);
            })->orderBy('created_at', 'desc')->get();
            $totals = [
                'total' => $results->count(),
                'competition' => 0,
                'friendly' => 0,
            ];
            foreach ($results as $result) {
                if (!$result->verified_at) {
                    continue;
                }
                $result->load('players');
                /** @var Result $result */
                if ($result->game && in_array($result->game->type_type, ['App\Models\League\Group', 'App\Models\TournamentRound'])) {
                    $totals['competition']++;
                } else {
                    $totals['friendly']++;
                }
                $stats[$result->players[$result->winner]->id]['wins']++;

                foreach ($result->sets as $key => $set) {
                    if ($key === 'tie_break') {
                        continue;
                    }
                    if ($set[0] > $set[1]) {
                        $stats[$result->players[0]->id]['sets']++;
                        if ($key === 0 && !$result->winner) {
                            $stats[$result->players[0]->id]['first_set_wins']++;
                        }
                    }
                    if ($set[0] < $set[1]) {
                        $stats[$result->players[1]->id]['sets']++;
                        if ($key === 0 && $result->winner) {
                            $stats[$result->players[1]->id]['first_set_wins']++;
                        }
                    }
                    $stats[$result->players[0]->id]['points'] += $set[0];
                    $stats[$result->players[1]->id]['points'] += $set[1];
                }
                if (isset($result->sets['tie_break'])) {
                    foreach ($result->sets['tie_break'] as $set) {
                        if (!$set[0] && !$set[1]) {
                            continue;
                        }
                        if ($set[0] > $set[1]) {
                            $stats[$result->players[0]->id]['tie_breaks']++;
                        }
                        if ($set[0] < $set[1]) {
                            $stats[$result->players[1]->id]['tie_breaks']++;
                        }
                        $stats[$result->players[0]->id]['points'] += $set[0];
                        $stats[$result->players[1]->id]['points'] += $set[1];
                    }
                }
            }
            $out = [
                'stats' => $stats,
                'totals' => $totals,
                'results' => ResultResource::collection($results->splice(0, 3))
            ];
            Cache::put('vs_stats_' . $player->id . '_' . $player2->id, $out, 60 * 60 * 24 * 7);
        }

        return $out;
    }

}
