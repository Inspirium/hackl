<?php

namespace App\Http\Controllers\V2\League;

use App\Actions\AssignPlayers;
use App\Actions\StartLeague;
use App\Http\Controllers\V2\Controller;
use App\Http\Resources\LeagueCollection;
use App\Http\Resources\LeagueResource;
use App\Models\League;
use App\Notifications\LeagueStart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return LeagueCollection
     */
    public function index(Request $request)
    {
        //return response()->json(Auth::id());
        $leagues = QueryBuilder::for(League::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                'name', 'show_on_tenisplus', 'classification', 'type',
                AllowedFilter::callback('show_in_club', function ($query, $value) use ($request) {
                    if (
                        $request->input('filter.player') != \Auth::id()
                         && Auth::id() != $request->input('filter.admins')
                    ) {
                        $query->where('show_in_club', 1);
                    }
                })->default(1),
                AllowedFilter::callback('admins', function ($query, $value) {
                    $query->whereHas('admins', function ($q) use ($value) {
                        $q->where('users.id', $value);
                    });
                }),
                AllowedFilter::callback('players', function ($query, $value) {
                    $query->whereHas('players', function ($q) use ($value) {
                        $q->where('teams.id', $value);
                    });
                }),
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::callback('player', function ($query, $player) {
                    $query->whereHas('players', function ($q) use ($player) {
                        $q->whereHas('players', function($q) use ($player) {
                            $q->where('player_team.player_id', $player);
                        })
                            ->orWhere([
                            ['league_player.user_id',  $player],
                            ['league_player.admin', 1]
                        ]);
                    })
                    /*->orWhereHas('admins', function ($q) use ($player) {
                        $q->where('users.id', $player);
                    })*/;
                }),
                AllowedFilter::callback('onboarding', function ($query, $value) {
                    if ($value) {
                        $query->where('finish_onboarding', '>=', now());
                    } else {
                        $query->where('finish_onboarding', '<', now());
                    }
                }),
            ])
            ->allowedSorts('finish_date', 'start_date',
                AllowedSort::callback('distance', function ($query, $direction) use ($request) {
                    $direction = $direction ? 'DESC' : 'ASC';
                    $query->join('clubs', 'leagues.club_id', '=', 'clubs.id')->orderByRaw("ST_Distance_Sphere(point(clubs.longitude, clubs.latitude), point(?, ?)) $direction", [
                        $request->input('longitude'),
                        $request->input('latitude'),
                    ]);
                }),
            )->defaultSort('-finish_date')
            ->allowedIncludes(['parent', 'child', 'groups', 'groups.games', 'players.players', 'club', 'thread', 'competition'])
            ->select('leagues.*')
        /*    ->toSql();
        return response()->json($leagues);
        */
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return new LeagueCollection($leagues);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->input('classification') === 'pyramid') {
            $validated = $request->validate([
                'name' => 'required',
                'players_in_groups' => 'sometimes|integer',
                'number_of_groups' => 'sometimes|integer',
                'rounds_of_play' => 'sometimes|integer',
                'points' => 'sometimes|integer',
                'move_up' => 'sometimes|integer',
                'move_down' => 'sometimes|integer',
                'playing_sets' => 'sometimes',
                'game_in_set' => 'sometimes',
                'last_set' => 'sometimes',
                'type' => 'sometimes',
                'description' => 'sometimes',
                'status' => 'sometimes',
                'points_loser' => 'sometimes',
                'points_match' => 'sometimes',
                'points_set_winner' => 'sometimes',
                'finish_date' => 'sometimes',
                'start_date' => 'sometimes',
                'finish_onboarding' => 'sometimes',
                'boarding_type' => 'sometimes|boolean',
                'classification' => 'sometimes',
                'is_doubles' => 'sometimes',
                'freeze' => 'sometimes',
                'show_tournament' => 'sometimes',
                'show_on_tenisplus' => 'sometimes|boolean',
                'show_in_club' => 'sometimes|boolean',
            ]);
        } else {
            $validated = $request->validate([
                'name' => 'required',
                'players_in_groups' => 'required|integer',
                'number_of_groups' => 'required|integer',
                'rounds_of_play' => 'required|integer',
                'points' => 'required|integer',
                'move_up' => 'sometimes|integer',
                'move_down' => 'sometimes|integer',
                'playing_sets' => 'required',
                'game_in_set' => 'required',
                'last_set' => 'required',
                'type' => 'required',
                'description' => 'sometimes',
                'status' => 'sometimes',
                'points_loser' => 'sometimes',
                'points_match' => 'sometimes',
                'points_set_winner' => 'sometimes',
                'finish_date' => 'sometimes',
                'start_date' => 'sometimes',
                'finish_onboarding' => 'sometimes',
                'boarding_type' => 'sometimes|boolean',
                'classification' => 'sometimes',
                'is_doubles' => 'sometimes',
                'freeze' => 'sometimes',
                'show_tournament' => 'sometimes',
                'groups_custom_points' => 'sometimes|boolean',
                'show_on_tenisplus' => 'sometimes|boolean',
                'show_in_club' => 'sometimes|boolean',
            ]);
        }
        $validated['club_id'] = $request->get('club')->id;
        $league = League::create($validated);

        for ($i = 1; $i <= $league->number_of_groups; $i++) {
            League\Group::create([
                'name' => 'Grupa '.$i,
                'order' => $i,
                'move_up' => $league->move_up,
                'move_down' => $league->move_down,
                'players_in_group' => $league->players_in_groups,
                'league_id' => $league->id,
            ]);
        }

        return LeagueResource::make($league);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function show($league)
    {
            $league = QueryBuilder::for(League::where('id', $league))
                ->allowedIncludes([
                    'players', 'admins', 'groups', 'groups.games', 'groups.thread', 'documents', 'parent', 'child', 'players.players', 'thread',
                    'competition'
                ])
                ->first();
            return new LeagueResource($league);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league)
    {
        $validated = $request->validate(['name' => 'sometimes',
            'players_in_groups' => 'sometimes|integer',
            'rounds_of_play' => 'sometimes|integer',
            'number_of_groups' => 'sometimes|integer',
            'points' => 'sometimes|integer',
            'move_up' => 'sometimes|integer',
            'move_down' => 'sometimes|integer',
            'playing_sets' => 'sometimes',
            'game_in_set' => 'sometimes',
            'last_set' => 'sometimes',
            'type' => 'sometimes',
            'description' => 'sometimes',
            'status' => 'sometimes',
            'points_loser' => 'sometimes',
            'points_match' => 'sometimes',
            'points_set_winner' => 'sometimes',
            'finish_date' => 'sometimes',
            'start_date' => 'sometimes',
            'finish_onboarding' => 'sometimes',
            'boarding_type' => 'sometimes|nullable|boolean',
            'freeze' => 'sometimes',
            'show_tournament' => 'sometimes',
            'groups_custom_points' => 'sometimes|boolean',
            'show_on_tenisplus' => 'sometimes',
            'show_in_club' => 'sometimes|boolean',
        ]);
        $no_groups = $league->number_of_groups;
        $old_status = $league->status;
        $league->update($validated);

        if ($no_groups !== $league->number_of_groups) {
            // update groups
            if ($no_groups > $league->number_of_groups) {
                $groups = $league->groups()->orderBy('order')->get();
                $i = 1;
                foreach ($groups as $group) {
                    if ($i <= $league->number_of_groups) {
                        $i++;
                        continue;
                    }
                    $group->delete();
                    $i++;
                }
            }
            if ($no_groups < $league->number_of_groups) {
                $i = $no_groups + 1;
                while ($i <= $league->number_of_groups) {
                    League\Group::create([
                        'name' => 'Grupa '.$i,
                        'order' => $i,
                        'move_up' => $league->move_up,
                        'move_down' => $league->move_down,
                        'players_in_group' => $league->players_in_groups,
                        'league_id' => $league->id,
                    ]);
                    $i++;
                }
            }
        }

        if (isset($validated['status']) && $validated['status'] == 4 && $old_status != $validated['status'] && $old_status != 5) {
            // start league
            StartLeague::execute($league);
           // $players = collect([]);
            foreach ($league->players as $team) {
                //$players = $players->merge($team->players);
                foreach ($team->players as $player) {
                    $player->notify((new LeagueStart($league, Auth::user()))->locale($player->lang));
                }
            }
            // Notification::send($players, new LeagueStart($league, Auth::user()));

        }

        return LeagueResource::make($league);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\League  $league
     * @return \Illuminate\Http\Response
     */
    public function destroy(League $league)
    {
        $league->delete();

        return response()->noContent();
    }

    public function randomLeague(League $league)
    {
        AssignPlayers::random($league);
    }

    public function strengthLeague(League $league)
    {
        AssignPlayers::strength($league);
    }

    public function inheritLeague(League $league)
    {
        $total = $league->parent->groups->count();
        for ($i = 1; $i <= $total; $i++) {
            // take group
            $group = $league->parent->groups()->where('order', $i)->firstOrFail();
            $new_group = $league->groups()->where('order', $i)->with('players')->firstOrFail();
            $players = $group->players()->orderBy('position')->get();
            if ($i === 1) {
                // take players from first group that are staying
                $count = $players->count() - $group->move_down;
                $ps = $players->splice(0, $count);
                $j = 1;
                foreach ($ps as $one) {
                    $new_group->players()->attach($one, ['player' => 1, 'position' => $j]);
                    $j++;
                }
                // take players from second group that are moving up
                $group2 = $league->parent->groups()->where('order', $i + 1)->firstOrFail();
                $count2 = $group2->move_up;
                $ps = $group2->players()->orderBy('position')->get()->splice(0, $count2)->all();
                foreach ($ps as $one) {
                    $new_group->players()->attach($one, ['player' => 1, 'position' => $j]);
                    $j++;
                }
            } else if ($i === $total) {
                $new_group = $league->groups()->where('order', $i)->with('players')->firstOrFail();
                // get players from previous group
                $group2 = $league->parent->groups()->where('order', $i - 1)->firstOrFail();
                $count = $group2->move_down;
                $j = 1;
                if ($count) {
                    $ps = $group2->players()->orderBy('position')->get()->splice($count)->all();
                    foreach ($ps as $one) {
                        $new_group->players()->attach($one, ['player' => 1, 'position' => $j]);
                        $j++;
                    }
                }
                // take players from last group that are staying
                $count = $players->count() - $group->move_up;
                $ps = $players->splice($count-1)->all();
                foreach ($ps as $one) {
                    $new_group->players()->attach($one, ['player' => 1, 'position' => $j]);
                    $j++;
                }
            } else {
                // get players from previous group
                $group2 = $league->parent->groups()->where('order', $i - 1)->firstOrFail();
                $count = $group2->move_down;
                $j = 1;
                if ($count) {
                    $ps = $group2->players()->orderBy('position')->get()->splice($count-1)->all();
                    foreach ($ps as $one) {
                        $new_group->players()->attach($one, ['player' => 1, 'position' => $j]);
                        $j++;
                    }
                }
                // get players that are staying
                $count = $players->count() - $group->move_down - $group->move_up;
                if ($count > 0) {
                    $ps = $group->players()->orderBy('position')->get()->splice($group->move_up-1, $count)->all();
                    foreach ($ps as $one) {
                        $new_group->players()->attach($one, ['player' => 1, 'position' => $j]);
                        $j++;
                    }
                }
                // get players from next group
                $group2 = $league->parent->groups()->where('order', $i + 1)->firstOrFail();
                $count = $group2->move_up;
                $ps = $group2->players()->orderBy('position')->get()->splice(0, $count)->all();
                foreach ($ps as $one) {
                    $new_group->players()->attach($one, ['player' => 1, 'position' => $j]);
                    $j++;
                }
            }
            /*
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
            }*/
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

    public function copyLeague(League $league)
    {
        for ($i = 0; $i < $league->parent->groups->count(); $i++) {
            $group = $league->parent->groups[$i];


            foreach ($group->players()->orderBy('position')->get() as $one) {
                $league->groups[$i]->players()->attach($one, ['player' => 1]);
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

    public function clearLeague(League $league)
    {
        foreach ($league->groups as $group) {
            $group->players()->detach();
        }

        return LeagueResource::make($league);
    }

    public function startNew(League $league)
    {
        $league->load(['admins', 'players', 'groups', 'documents']);
        $l = League::create([
            'name' => $league->name,
            'description' => $league->description,
            'type' => $league->type,
            'club_id' => $league->club_id,
            'number_of_groups' => $league->number_of_groups,
            'players_in_groups' => $league->players_in_groups,
            'rounds_of_play' => $league->rounds_of_play,
            'points' => $league->points,
            'move_up' => $league->move_up,
            'move_down' => $league->move_down,
            'playing_sets' => $league->playing_sets,
            'game_in_set' => $league->game_in_set,
            'last_set' => $league->last_set,
            'points_loser' => $league->points_loser,
            'points_match' => $league->points_match,
            'parent_id' => $league->id,
            'status' => 1,
            'classification' => $league->classification,
            'is_doubles' => $league->is_doubles,
        ]);
        $l->admins()->syncWithPivotValues($league->admins, ['admin' => 1]);
        $l->players()->syncWithPivotValues($league->players, ['player' => 1]);

        $l->documents()->sync($league->documents);
        $total = $league->groups->count();
        for ($i = 0; $i < $total; $i++) {
            /** @var League\Group $group */
            $group = $league->groups[$i];
            $g = League\Group::create([
                'league_id' => $l->id,
                'order' => $group->order,
                'name' => $group->name,
                'move_up' => $group->move_up,
                'move_down' => $group->move_down,
                'players_in_group' => $group->players_in_group,
            ]);
            $g->save();
        }

        return LeagueResource::make($l);
    }

    public function groupOrder(Request $request, League $league)
    {
        $order_array = $request->input('order');
        foreach ($order_array as $order => $group_id) {
            League\Group::find($group_id)->update(['order' => intval($order)]);
        }
    }
}
