<?php

namespace App\Http\Controllers\V2;

use App\Actions\SaveImageAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teams = QueryBuilder::for(Team::class)
            ->allowedIncludes(['clubs', 'players'])
            ->allowedFilters([
                AllowedFilter::exact('number_of_players'),
                AllowedFilter::callback('players', function ($query, $value) {
                    $query->whereHas('players', function ($query) use ($value) {
                        $query->where('users.id', $value);
                    });
                }),
                AllowedFilter::callback('display_name', function ($query, $value) {
                    $query
                        //->where('number_of_players', \request()->input('filter.number_of_players'))
                        ->where(function($query) use ($value) {
                        $query->where('display_name', 'LIKE', "%$value%")
                            ->orWhereHas('players', function($query) use ($value) {
                                $query->where('last_name', 'LIKE', "%$value%");
                                //if (\request()->input('filter.number_of_players') == 1) {
                                    $query->orWhere('first_name', 'LIKE', "%$value%");
                                //}
                            });
                            });
                })->ignore(null),
                AllowedFilter::callback('age', function ($query, $value) {
                    $query->whereHas('primary_contact', function($query) use ($value) {
                        $query->where('birthyear', '<=', (date('Y') - $value[0]))
                            ->where('birthyear', '>=', (date('Y') - $value[1]));
                    });
                })->ignore(null),
                AllowedFilter::callback('power', function ($query, $value) {
                    $query->where('power_club', '>=', $value[0])->where('power_club', '<=', $value[1]);
                })->ignore(null),
                AllowedFilter::callback('club', function ($query, $club) use ($request) {
                    $query->whereHas('clubs', function ($query) use ($club, $request) {
                        $query->where('club_id', $club);
                    });
                })->ignore(null),
                AllowedFilter::callback('is_doubles', function ($query, $value) {
                    if ($value) {
                        $query->where('number_of_players', 2);
                    }
                }),
                AllowedFilter::callback('multiple_clubs', function($query) {
                    $query->has('clubs', '>', 1);
                }),
                AllowedFilter::callback('has_school_group', function($query, $value) {
                    if ($value) {
                        $query->whereHas('school_groups', function ($query) {
                            $query->where('school_groups.club_id', \request()->input('filter.club') ?? \request()->get('club')->id);
                        });
                    }
                }),
            ])
            ->allowedSorts([
                AllowedSort::callback('random', function($query) {
                    $query->orderByRaw('RAND()');
                }),
                AllowedSort::callback('rank', function(\Illuminate\Database\Eloquent\Builder $query) use ($request) {
                    if ($request->input('filter.club')) {
                        $query->join('club_team', 'teams.id', '=', 'club_team.team_id')
                            ->whereNot('club_team.rank', 0)
                            ->where('club_team.club_id', $request->input('filter.club'))
                            ->orderBy('ctrank', 'asc')
                            ->select(['teams.*', 'club_team.rank as ctrank', 'club_team.rating as ctrating', 'club_team.team_id', 'club_team.club_id']);
                    } else {
                        $query
                            ->whereNot('rank', 0)
                            ->orderBy('rank', 'asc');
                    }
                })
            ])
            ->with(['primary_contact'])
            ->paginate($request->input('limit'))
            ->appends($request->query());
        return TeamResource::collection($teams);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'display_name' => 'sometimes',
            'image' => 'sometimes|nullable|image64:jpeg,jpg,png',
            'number_of_players' => 'sometimes|numeric',
            'primary_contact_id' => 'sometimes',
        ]);

        $image = isset($validated['image']) ? $validated['image'] : false;
        unset($validated['image']);
        if ($image) {
            $validated['image'] = $saveImageAction->execute($image, 'avatars');
        }
        if (!isset($validated['number_of_players']) || !$validated['number_of_players']) {
            $validated['number_of_players'] = 2;
        }

        $team = Team::create($validated);

        if ($request->input('players')) {
            foreach ($request->input('players') as $player) {
                $team->players()->attach($player['id']);
            }
        } else {
            $team->players()->attach(\Auth::id());
        }

        if (!$team->primary_contact) {
            $team->primary_contact()->associate($request->input('players.0')['id']);
        }

        $ids = $team->primary_contact->clubs()->pluck('clubs.id');
        $team->clubs()->attach($ids);


        return TeamResource::make($team);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return TeamResource::make($team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'display_name' => 'sometimes',
            'new_image' => 'sometimes|nullable|image64:jpeg,jpg,png',
            'primary_contact_id' => 'sometimes'
        ]);
        $image = isset($validated['new_image']) ? $validated['new_image'] : false;
        unset($validated['new_image']);
        if ($image) {
            $validated['image'] = $saveImageAction->execute($image, 'avatars');
        }
        $team->update($validated);
        if ($request->has('players')) {
            $team->players()->sync(collect($request->input('players'))->map(fn ($i) => $i['id']));
        }

        return TeamResource::make($team);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->delete();

        return response()->noContent();
    }
}
