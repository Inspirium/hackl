<?php

namespace App\Http\Controllers\V2;

use App\Actions\CreateGame;
use App\Actions\ScoreGame;
use App\Actions\VSStats;
use App\Events\LeagueUpdate;
use App\Http\Resources\ResultCollection;
use App\Http\Resources\ResultResource;
use App\Models\Game;
use App\Models\League;
use App\Models\Result;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentRound;
use App\Models\User;
use App\Notifications\AdminError;
use App\Notifications\RequestResultVerificationNotification;
use App\Notifications\ResultDisputed;
use App\Notifications\ResultVerified;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return ResultCollection
     */
    public function index(Request $request)
    {
        if (!$request->get('club')) {
            return [];
        }
        if ($request->has('filter.players')) {
            return $this->vs();
        }
        if ($request->has('filter.playersStats')) {
            return $this->vsStats();
        }
        $club = $request->get('club')->id;
        $results = QueryBuilder::for(Result::class)
            ->allowedIncludes(['game', 'sport'])
            ->allowedFilters([
                AllowedFilter::callback('type', function ($query, $type) use ($club) {
                    if ($type === 'all') {
                        $query->whereNull('non_member')->whereHas('players', function ($query) use ($club) {
                            $query->whereHas('clubs', function ($q) use ($club) {
                                $q->where('id', $club);
                            });
                        });
                    }
                    if ($type === 'official') {
                        $query->where('official', true)->whereNull('non_member')
                            ->whereHas('players', function ($query) use ($club) {
                                $query->whereHas('clubs', function ($q) use ($club) {
                                    $q->where('id', $club);
                                });
                            });
                    }
                    if ($type === 'friendly') {
                        $query->where('official', false)->whereNull('non_member')
                            ->whereHas('players', function ($query) use ($club) {
                                $query->whereHas('clubs', function ($q) use ($club) {
                                    $q->where('id', $club);
                                });
                            });
                    }
                }),
                AllowedFilter::callback('player', function ($query, $player) {
                    $query->whereHas('players', function ($q) use ($player) {
                        $q->whereHas('players', function($q) use ($player) {
                            $q->where('player_team.player_id', $player);
                        });
                    });
                }),
                AllowedFilter::callback('team', function($query, $team) {
                    $query->whereHas('players', function($q) use ($team) {
                        $q->where('teams.id', $team);
                    });
                }),
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::exact('sport', 'sport_id'),
            ])
            ->allowedSorts([
                'id', AllowedSort::field('date', 'created_at'),
            ])->defaultSort('-id')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return new ResultCollection($results);
    }

    private function vs()
    {
        $players = explode(',', request()->input('filter.players'));
        if ($players[0] === $players[1]) {
            return \response()->noContent();
        }
        $player = Team::find($players[0]);
        if (!$player) {
            return response()->noContent();
        }
        $player2 = Team::find($players[1]);
        if (!$player2) {
            return response()->noContent();
        }
        $results = $player->results()->whereHas('players', function ($query) use ($player2) {
            $query->where('participant_id', $player2->id);
        })->paginate(request()->input('limit'))->appends(request()->query());

        return new ResultCollection($results);
    }

    private function vsStats()
    {
        $players = explode(',', request()->input('filter.playersStats'));
        if ($players[0] === $players[1]) {
            return \response()->noContent();
        }
        $player = Team::find($players[0]);
        $player2 = Team::find($players[1]);
        if (!$player2) {
            return \response()->noContent();
        }
        $vsStats = new VSStats();
        $stats = $vsStats->handle($player, $player2);

        return response()->json($stats);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return ResultResource
     */
    public function store(Request $request)
    {
        $p1 = $request->input('players.0.id');
        $p2 = $request->input('players.1.id');
        if (!$request->input('players.1.type')) {
            return \response()->json(['no data'], 403);
        }
        $surface = $request->input('surface.id');
        $sets = $request->input('sets');
        $result = new Result();
        $result->surface_id = $surface;
        if ($request->input('tie_break')) {
            $sets['tie_break'] = $request->input('tie_break');
        }
        $result->sets = $sets;
        $result->date = Carbon::now();
        $result->club_id = $request->get('club')->id;
        if ($request->input('game_id')) {
            $result->game_id = $request->input('game_id');
            $game = Game::find($request->input('game_id'));
            if ($game->type_type === League\Group::class) {
                $result->club_id = $game->type->league->club->id;
            }
            if ($game->type_type === TournamentRound::class) {
                $result->club_id = $game->type->tournament->club->id;
            }
        }
        // TODO:
        //$result->official = (bool) $request->input('official');
        //$result->rated = (bool) $request->input('official');
        $result->official = true;
        $result->rated = true;
        if ($request->input('sport.id')) {
            $result->sport_id = $request->input('sport.id');
        }
        $result->save();
        if ($p1) {
            //check type
            $type = $request->input('players.0.type');
            if ($type === 'player') {
                //find team and attach
                $team1 = Team::query()->where('number_of_players', 1)->where('primary_contact_id', $p1)->first();
                $result->teams()->attach($team1->id, ['verified' => $p1 === Auth::id()]);
            }
            if ($type === 'team') {
                // check if team player is entering result
                $team1 = Team::with('players')->find($p1);
                $verify = $team1->players->filter(function ($player) {
                    return $player->id === Auth::id();
                })->count();
                $result->teams()->attach($team1->id, ['verified' => !!$verify]);
            }
            if ($p2) {
                $team2 = false;
                $type = $request->input('players.1.type');
                if ($type === 'player') {
                    //find team and attach
                    $team2 = Team::query()->where('number_of_players', 1)->where('primary_contact_id', $p2)->first();
                    $result->teams()->attach($team2->id);
                }
                if ($type === 'team') {
                    // check if team player is entering result
                    $team2 = Team::with('players')->find($p2);
                    $result->teams()->attach($team2);
                }
                if ($team2) {
                    //notify team2
                    // \Notification::send($team2->players, new RequestResultVerificationNotification($result, Auth::user()));
                    foreach($team2->players as $player) {
                        $player->notify((new RequestResultVerificationNotification($result, Auth::user()))->locale($player->lang));
                    }
                }
            }
        }

        if ($request->input('game_id')) {
            $result->game->played_at = $result->date;
            $result->game->save();
            ScoreGame::score($result->game);
        } elseif ($request->input('league_id')) {
            // create game
            /** @var League $league */
            $league = League::find($request->input('league_id'));
            /** @var Game $game */
            $game = CreateGame::create($league->groups->first(), [$team1, $team2]);
            $game->played_at = $result->date;
            $game->result()->save($result);
            $game->save();
            //ScoreGame::score($game);
            \broadcast(new LeagueUpdate($league));
        }

        return new ResultResource($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  Result  $result
     * @return ResultResource
     */
    public function show($result)
    {
        $result = QueryBuilder::for(Result::where('id', $result))
            ->allowedIncludes(['game', 'sport'])->first();
        return new ResultResource($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result $result)
    {
        $sets = $request->input('sets');
        if ($request->input('tie_break')) {
            $sets['tie_break'] = $request->input('tie_break');
        }
        $result->sets = $sets;
        $result->save();

        return ResultResource::make($result);
    }

    public function dispute(Result $result)
    {
        if (! $result->verified && (Auth::user()->is_admin || $result->players[1]->id === Auth::id())) {
            //\Notification::send($result->players[0]->players, new ResultDisputed($result, Auth::user()));
            foreach($result->players[0]->players as $player) {
                $player->notify((new ResultDisputed($result, Auth::user()))->locale($player->lang));
            }
            $result->delete();
        }
    }

    public function verify(Result $result)
    {
        if ($result->verified_at) {
            return response()->json(['error' => 'Result already verified'], 403);
        }
        $result->players()->updateExistingPivot(Auth::id(), ['verified' => true]);
        $result->verified_at = 1;
        $result->verified_at = Carbon::now();
        $result->save();
        //\Notification::send($result->players[0]->players, new ResultVerified($result, Auth::user()));
        foreach ($result->players[0]->players as $player) {
            $player->notify((new ResultVerified($result, Auth::user()))->locale($player->lang));
        }
        if ($result->game) {
            ScoreGame::score($result->game);
        }

        return new ResultResource($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        if (Auth::user()->is_admin || (! $result->verified && $result->players[0]->id === Auth::id())) {
            $result->delete();
        }

        return response()->noContent();
    }
}
