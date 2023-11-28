<?php

namespace App\Http\Controllers\V2\Tournament;

use App\Actions\ATPScore;
use App\Http\Controllers\Controller;
use App\Http\Resources\TournamentCollection;
use App\Http\Resources\TournamentResource;
use App\Models\League;
use App\Models\Tournament;
use App\Models\TournamentTypes\SingleElimination;
use App\Notifications\TournamentStart;
use Carbon\Carbon;
use Illuminate\Database\Schema\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return TournamentCollection
     */
    public function index(Request $request)
    {
        $tournaments = QueryBuilder::for(Tournament::class)
            ->allowedFilters(['status',
                'name', 'show_on_tenisplus',
                AllowedFilter::callback('show_in_club', function($query, $value) use ($request) {
                    if (($request->input('filter.mine') && $request->input('filter.mine') != \Auth::id()) ||
                        ($request->input('filter.player') && $request->input('filter.player') == \Auth::id())) {
                        $query->where('show_in_club', 1);
                    }
                })->default(1),
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::callback('mine', function ($query, $value) {
                    $query
                        /*->whereHas('admins', function($query) use ($value) {
                        $query->where('player_id', $value);
                    })*/
                        ->whereHas('players', function($q) use ($value) {
                        $q->whereHas('players', function($q) use ($value) {
                            $q->where('player_team.player_id', $value);
                        });
                    });
                }),
                AllowedFilter::callback('admins', function ($query, $value) {
                    $query->whereHas('admins', function($query) use ($value) {
                        $query->where('player_id', $value);
                    });
                }),
                AllowedFilter::callback('players', function ($query, $value) {
                    $query->whereHas('players', function ($q) use ($value) {
                        $q->where('teams.id', $value);
                    });
                }),
                AllowedFilter::callback('final_status', function($query, $value) {
                   $query->whereHas('players', function($q) use ($value) {
                       $q->where('final_status', $value);
                   });
                }),
                AllowedFilter::callback('player', function ($query, $player) {
                    $query->whereHas('players', function ($q) use ($player) {
                        $q->whereHas('players', function($q) use ($player) {
                            $q->where('player_team.player_id', $player);
                        });
                    })->orWhereHas('admins', function ($q) use ($player) {
                        $q->where('player_id', $player);
                    });
                }),
                AllowedFilter::callback('onboarding', function ($query, $value) {
                    if ($value) {
                        $query->where('application_deadline', '>=', Carbon::now());
                    } else {
                        $query->where('application_deadline', '<', Carbon::now());
                    }
                }),
            ])
            ->allowedSorts(
                'active_to', 'active_from',
                AllowedSort::callback('distance', function ($query, $direction) use ($request) {
                    $direction = $direction ? 'DESC' : 'ASC';
                    $query->join('clubs', 'tournaments.club_id', '=', 'clubs.id')->orderByRaw("ST_Distance_Sphere(point(clubs.longitude, clubs.latitude), point(?, ?)) $direction", [
                        $request->input('longitude'),
                        $request->input('latitude'),
                    ]);
                }),
            )
            ->withCount(['players'])
            ->allowedIncludes(['players', 'club', 'thread'])
            //->select(['tournaments.*', 'players_count'])

            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return new TournamentCollection($tournaments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $c = new SingleElimination();
        $validated = $c->validate($request);

        $data = $c->prepareData($validated, null);

        /** @var Tournament $tournament */
        $tournament = Tournament::create($data);

        if ($request->has('league') || $request->has('league_id')) {
            $league = League::find($request->input('league.id') ?? $request->input('league_id'));
            $tournament->league()->associate($league);
        }

        $tournament->club()->associate($request->get('club'));

        $tournament->admins()->attach(\Auth::id());

        $tournament->save();

        $c->createGames($tournament);

        return TournamentResource::make($tournament);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show($tournament)
    {
        $tournament = QueryBuilder::for(Tournament::where('id', $tournament))
            ->allowedIncludes([
                'players', 'admins', 'documents', 'rounds', 'rounds.games', 'league', 'thread'
            ])
            ->first();

        return new TournamentResource($tournament);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tournament $tournament)
    {
        $old_status = $tournament->status;
        $c = new SingleElimination();
        $validated = $c->validate($request);

        $data = $c->prepareData($validated, $tournament);
        $tournament->update($data);

        if ($request->input('createGames')) {
            $c->createGames($tournament);
        }

        if (isset($validated['status']) && $validated['status'] == 4 && $old_status != $validated['status']) {
            $players = collect([]);
            foreach ($tournament->players as $team) {
                $players = $players->merge($team->players);
            }
            // Notification::send($players, new TournamentStart($tournament, \Auth::user()));
            foreach($players as $player) {
                $player->notify((new TournamentStart($tournament, \Auth::user()))->locale($player->lang));
            }
            // Notification::send($tournament->admins, new TournamentStart($tournament, \Auth::user()));
            foreach($tournament->admins as $admin) {
                $admin->notify((new TournamentStart($tournament, \Auth::user()))->locale($admin->lang));
            }
        }
        if (isset($validated['status']) && $validated['status'] == 5 && $old_status != $validated['status']) {
            // tournament has finished
            // score if tournament has scoring
            if ($tournament->data['cup_scoring']) {
                $round = $tournament->rounds()->orderBy('order', 'desc')->first();
                $game = $round->games()->first();
                // get all players, get their final rounds and add scores accordingly
                foreach ($tournament->players as $team) {
                    $status =  $team->pivot->final_status;
                    $score = ATPScore::get($status, (int)$tournament->data['cup_scoring'], $tournament->rounds()->count());
                    $tournament->players()->updateExistingPivot($team->id, ['final_score' => $score, 'final_at' => $game->played_at]);
                }
            }
        }

        return new TournamentResource($tournament);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return response()->noContent();
    }

    public function pullPlayersFromLeague(Request $request, Tournament $tournament, League $league)
    {
        $i = 1;
        foreach ($league->groups as $group) {
            foreach ($group->players as $player) {
                $tournament->players()->attach($player->id, ['seed' => $i, 'player' => 1]);
                $i++;
            }
        }

        return response()->noContent();

        $total = $league->parent->groups->count();
        for ($i = 0; $i < $total; $i++) {
            $group = $league->parent->groups[$i];
            $group->load(['players']);
            // move up players from current group
            if ($i > 0) {
                $move_up = $group->players->splice(0, $group->move_up)->all();
                foreach ($move_up as $one) {
                    $league->groups[$i - 1]->players()->attach($one, ['player' => 1]);
                }
            }
            // add standing players
            if ($i === 0) {
                $move_up_stading = 0;
                $diff_stading = $group->players_in_group - $group->move_down;
            } elseif ($i === $total - 1) {
                $move_up_stading = $group->move_up - 1;
                $diff_stading = $group->players_in_group - $group->move_up;
            } else {
                $move_up_stading = $group->move_up;
                $diff_stading = $group->players_in_group - $group->move_up - $group->move_down;
            }
            $standing = $group->players->splice($move_up_stading, $diff_stading)->all();

            foreach ($standing as $one) {
                $league->groups[$i]->players()->attach($one, ['player' => 1]);
            }
            // move down players
            if ($i < $total - 1) {
                $move_down = $group->players->splice(-$group->move_down)->all();
                foreach ($move_down as $one) {
                    $league->groups[$i + 1]->players()->attach($one, ['player' => 1]);
                }
            }
        }
        foreach ($league->groups as $group) {
            $group->load(['players']);
            $count = $group->players->count();
            if ($count != $group->players_in_group) {
                $group->players_in_group = $count;
                $group->save();
            }
        }
    }

    private function clearGames(Tournament $tournament)
    {
        foreach ($tournament->rounds as $round) {
            foreach ($round->games as $game) {
                $game->players()->detach();
            }
        }
    }

    public function seed(Tournament $tournament)
    {
        $this->clearGames($tournament);
        $c = new SingleElimination();
        $c->fillGames($tournament);

        return response()->noContent();
    }

    public function random(Tournament $tournament)
    {
        $this->clearGames($tournament);
        $c = new SingleElimination();
        $tournament->players()->updateExistingPivot($tournament->players->map(function ($item) {
            return $item->id;
        }), ['seed' => null]);
        $c->fillGames($tournament);

        return response()->noContent();
    }

    public function strength(Tournament $tournament)
    {
        $this->clearGames($tournament);
        $c = new SingleElimination();
        $players = $tournament->players()->join('club_team', 'teams.id', '=', 'club_team.team_id')->where('club_team.club_id', $tournament->club_id)->orderBy('club_team.rating', 'desc')->get();
        $i = 1;
        foreach ($players as $player) {
            $tournament->players()->updateExistingPivot($player->id, ['seed' => $i]);
            $i++;
        }

        $c->fillGames($tournament);

        return response()->noContent();
    }
}
