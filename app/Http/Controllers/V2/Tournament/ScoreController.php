<?php

namespace App\Http\Controllers\V2\Tournament;

use App\Http\Controllers\V2\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class ScoreController extends Controller
{
    public function index(Request $request) {
        $time = false;
        if ($request->has('filter.final_between')) {
            $time = explode(',', $request->input('filter.final_between'));
        }
        $tournament = $request->input('filter.tournament', 0);
        $club = $request->input('filter.club', 0);
        $team = $request->input('filter.team',0);
        $number = $request->input('filter.number_of_players', 0);
        $scores = DB::table('tournament_player')
            ->select( 'tournament_id', 'player_id', 'final_score', 'final_at',
                'player',
                DB::raw('sum(final_score) as tournament_total_score'),
                'teams.id'
            )
            ->leftJoin('teams', 'tournament_player.player_id', 'teams.id')
            ->leftJoin('tournaments', 'tournament_player.tournament_id', 'tournaments.id')
            ->where('tournament_player.player', 1)
            ->whereNotNull('tournament_player.final_score')
            ->when($number, function($query) use ($number) {
                $query->where('teams.number_of_players', $number);
            })
            ->when($time, function($query) use ($time) {
                $query->whereBetween('tournament_player.final_at', [ Carbon::parse($time[0], 'Europe/Zagreb'), Carbon::parse($time[1], 'Europe/Zagreb') ]);
            })
            ->when($tournament, function($query) use ($tournament) {
                $query->where('tournament_player.tournament_id', $tournament);
            })
            ->when($club, function($query) use ($club) {
                $query->where('tournaments.club_id', $club);
            })
            ->when($team, function($query) use ($team) {
                $query->where('tournament_player.player_id', $team);
            })
            ->groupBy('tournament_player.player_id')
            ->orderBy('tournament_total_score', 'desc')
            ->orderBy('tournament_player.id', 'asc')
            ->limit(15)
            ->offset(($request->input('page', 1) - 1) * 15)
            ->get()->keyBy('id');

        $players = QueryBuilder::for(Team::query()->whereIn('id', $scores->pluck('id')))
            ->allowedIncludes(['tournament_scores', 'tournament_scores.tournament'])
            ->get();
        $players = TeamResource::collection($players);
        foreach ($players as $player) {
            $player['tournament_total_score'] = $scores[$player->id]->tournament_total_score;
        }
        return array_values($players->sortByDesc('tournament_total_score', SORT_NUMERIC)->toArray());
    }
}
