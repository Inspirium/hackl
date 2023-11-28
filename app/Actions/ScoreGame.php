<?php

namespace App\Actions;

use App\Events\LeagueUpdate;
use App\Jobs\ProcessStats;
use App\Models\CompetitionScoring\AggregateLeagues;
use App\Models\Game;
use App\Models\League\Group;
use App\Models\Result;
use App\Models\TournamentRound;
use App\Models\User;
use App\Notifications\AdminError;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;
use RectorPrefix202304\Symfony\Component\Console\Output\ConsoleOutput;

class ScoreGame
{
    private static function scoreElo(Game $game)
    {
        /** @var Result $result */
        $result = $game->result;
        //User::find(1)->notify(new AdminError('Update ELO '. $game->id, 'Result: '. $result->id . ' verified: ' . $result->verified_at));
        /** @var Group $group */
        $group = $game->type;
        $player1 = $result->players[0];
        $player2 = $result->players[1];
        $p1 = $group->players()->where('league_group_player.player_id', $player1->id)->first();
        $p2 = $group->players()->where('league_group_player.player_id', $player2->id)->first();
        if (!$p1 | !$p2) {
            return;
        }

        $or1 = $r1 = $p1->pivot->score;
        $or2 = $r2 = $p2->pivot->score;
        $r1 = 10 ** ($r1 / 400);
        $r2 = 10 ** ($r2 / 400);
        $e1 = $r1 / ($r1 + $r2);
        $e2 = $r2 / ($r1 + $r2);
        $s1 = intval(!$result->winner);
        $s2 = intval($result->winner);
        $r1 = $or1 + 32 * ($s1 - $e1);
        $r2 = $or2 + 32 * ($s2 - $e2);

        $group->players()->updateExistingPivot($player1->id, ['score' => $r1]);
        $group->players()->updateExistingPivot($player2->id, ['score' => $r2]);
        Cache::delete('stats_group' . $group->id);
    }

    public static function scoreCup(Game $game)
    {
        $loser = false;
        if (in_array($game->is_surrendered, [1, 2])) {
            $l = $game->is_surrendered - 1;
            $winner = $game->players[!$l];
            if (isset($game->players[$l])) {
                $loser = $game->players[$l];
            }
        } else {
            // determine winner
            $winner = $game->result->players[$game->result->winner];
            $loser = $game->result->players[!$game->result->winner];
        }

        //move winner to next game in series
        /** @var TournamentRound $round */
        $round = TournamentRound::query()->where('tournament_id', $game->type->tournament_id)->where('order', $game->type->order + 1)->first();
        if ($round) {
            // gameInRound+1 \ 2
            $next_game_order = intdiv($game->order + 1, 2);
            $position = $game->order % 2 + 1;
            // TODO: maybe switch it up in even rounds...
            $new_game = Game::query()->where('order', $next_game_order)->where('type_type', '=', 'App\\Models\\TournamentRound')->where('type_id', $round->id)->first();
            if ($new_game) {
                $existing = $new_game->players()->wherePivot('order', $position)->first();
                if ($existing) {
                    $new_game->players()->detach($existing->id);
                }
                $new_game->players()->attach($winner->id, ['order' => $position]);
                $round->tournament->players()->updateExistingPivot($winner->id, ['final_status' => $round->order . '/' . $round->tournament->rounds->count()]);
                if ($loser) {
                    $round->tournament->players()->updateExistingPivot($loser->id, ['final_status' => ($round->order - 1) . '/' . $round->tournament->rounds->count()]);
                }
            }
        }else {
            $game->type->tournament->players()->updateExistingPivot($winner->id, ['final_status' => '*']);
        }
    }

    public static function score(Game $game)
    {
        //User::find(1)->notify(new AdminError('Scoring game ' . $game->id, $game->type_type));
        if ($game->type_type === TournamentRound::class) {
            return self::scoreCup($game);
        }

        if ($game->type->league->classification === 'pyramid') {
            self::scorePyramid($game);
        } else {
            self::scoreElo($game);
        }
        /*if ($game->type->league->competition_id) {
            $competition = $game->type->league->competition;
            $level = intval($game->type->name);
            $scoring = new AggregateLeagues();
            $scoring->handle($game, $competition, $level);
        }*/
        \broadcast(new LeagueUpdate($game->type->league));
    }

    private static function dump($value) {
        if (app()->runningInConsole()) {
            $output = new ConsoleOutput();
            $output->writeln("<info>$value</info>");
        }
    }

    private static function scorePyramid(Game $game)
    {
        /** @var Group $group */
        $group = $game->type;
        if (!$group) {
            return;
        }
        $league = $group->league;
        if (!$league) {
            return;
        }
        $points = $league->group_custom_points ? $group->points : $league->points;

        if (in_array($game->is_surrendered, [1, 2])) {
            $l = $game->is_surrendered - 1;
            $player = $game->players[!$l];
            $player = $group->players()->where('teams.id', $player->id)->first();
            self:dump($game->players[$l]->display_name . ' surrendered to ' . $player->display_name);
            $score_w = $player->pivot->score + $points;
            $group->players()->updateExistingPivot($player->id, ['score' => $score_w]);
            return;
        }
        $game->load(['result']);
        $w = (int) $game->result->winner;
        $l = (int) !$game->result->winner;
        $winner = $game->result->players[$w];
        $loser = $game->result->players[$l];
        self::dump($winner->display_name . ' won against ' . $loser->display_name);
        $wi = $group->players()->where('teams.id', $winner->id)->first();
        if ($wi) {
            $score_w = $wi->pivot->score;
            $has_wi = true;
        } else {
            $score_w = 0;
            $has_wi = false;
        }

        $li = $group->players()->where('teams.id', $loser->id)->first();
        if ($li) {
            $score_l = $li->pivot->score;
            $has_li = true;
        } else {
            $score_l = 0;
            $has_li = false;
        }


        // points for winning the match
        if ($points) {
            $score_w += $points;
        }

        // points for playing but losing the match
        $points_match = $league->group_custom_points ? $group->points_match : $league->points_match;
        if ($points_match) {
            $score_l += $points_match;
        }

        // get number of won/lost sets
        $p1 = 0;
        $p2 = 0;
        foreach ($game->result->sets as $key => $set) {
            if ($key === 'tie_break') {
                continue;
            }
            if ($set[0] > $set[1]) {
                $p1++;
            }
            if ($set[0] < $set[1]) {
                $p2++;
            }
        }
        // loser gets point for every set won
        $points_loser = $league->group_custom_points ? $group->points_loser : $league->points_loser;
        if ($points_loser) {
            if ($l) { // player2 is loser, get p2 points
                $score_l += $p2 * $points_loser;
            } else {
                $score_l += $p1 * $points_loser;
            }
        }
        // winner loses point for every set lost
        $points_set_winner = $league->points_set_winner;
        if ($points_set_winner) {
            if ($l) { // player1 is winner, deduct wins from player2
                $score_w -= $p2 * $points_set_winner;
            } else {
                $score_w -= $p1 * $points_set_winner;
            }
        }
        self::dump($winner->display_name . ' won ' . $score_w . ' points');
        self::dump($loser->display_name . ' won ' . $score_l . ' points');
        if ($has_wi) {
            $group->players()->updateExistingPivot($winner->id, ['score' => $score_w]);
        }
        if ($has_li) {
            $group->players()->updateExistingPivot($loser->id, ['score' => $score_l]);
        }
    }

    // TODO this shit
    public static function unscore(Game $game)
    {
        // clear public elo
        $result = Result::withTrashed()->where('game_id', $game->id)->orderBy('deleted_at', 'desc')->first();
        if ($result) {
            $score = $result->points;
            $winner = $result->winner;
            foreach ($result->players as $index => $player) {
                if ($index == $winner) {
                    $player->rating = $player->rating - $score;
                } else {
                    $player->rating = $player->rating + $score;
                }
                $player->save();
            }
        }
        if (!$game->type_type) {
            return;
        }
        if ($game->type_type !== Group::class) {
            return;
        }

        /** @var Group $group */
        $group = $game->type;
        if (!$group) {
            return;
        }
        $group->load('league');
        $league = $group->league;
        if (!$league) {
            return;
        }
        $c = new RecalculateLeagueELO();
        $c->handle($league);

        return;

        /*if (in_array($game->is_surrendered, [1, 2])) {
            $l = $game->is_surrendered - 1;
            $player = $game->players[!$l];
            $player = $group->players()->where('teams.id', $player->id)->first();
            $points = $group->points ?? $league->points;
            $score_w = $player->pivot->score - $points;
            $group->players()->updateExistingPivot($player->id, ['score' => $score_w]);

            return;
        }
        $game_result = Result::withTrashed()->where('game_id', $game->id)->first();
        if ($game_result) {
            $w = (int)$game_result->winner;
            $l = (int)!$game_result->winner;
            $winner = $game_result->players[$w];
            $loser = $game_result->players[$l];
            $wi = $group->players()->where('teams.id', $winner->id)->first();
            $score_w = $wi->pivot->score;
            $li = $group->players()->where('teams.id', $loser->id)->first();
            $score_l = $li->pivot->score;
            $points = $group->points ?? $league->points;
            if ($points) {
                $score_w = $score_w - $points;
            }
            $points_match = $group->points_match ?? $league->points_match;
            if ($points_match) {
                $score_l = $score_l - $points_match;
            }
            $points_loser = $group->points_loser ?? $league->points_loser;
            if ($points_loser) {
                $p1 = 0;
                $p2 = 0;
                foreach ($game_result->sets as $key => $set) {
                    if ($key === 'tie_break') {
                        continue;
                    }
                    if ($set[0] > $set[1]) {
                        $p1++;
                    }
                    if ($set[0] < $set[1]) {
                        $p2++;
                    }
                }
                if ($l) {
                    $score_l = $score_l - $p2 * $points_loser;
                } else {
                    $score_l = $score_l - $p1 * $points_loser;
                }
            }

            $group->players()->updateExistingPivot($winner->id, ['score' => $score_w]);
            $group->players()->updateExistingPivot($loser->id, ['score' => $score_l]);
            var_dump($score_w);
        }*/
    }
}
