<?php

namespace App\Http\Controllers\V2\League;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\League;
use App\Models\League\Group;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $league)
    {
        $league_groups = QueryBuilder::for(League::where('id', $league)->players())
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return $league_groups;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $league_group)
    {
        $players = $request->input('players');
        foreach ($players as $player) {
            $league_group->players()->attach($player['id'], ['player' => true]);
        }

        return UserCollection::make($league_group->players);
    }

    public function show($league_group)
    {
        $lg = QueryBuilder::for(Group::where('id', $league_group))
            ->allowedIncludes(['players', 'thread'])
            ->first();
        return response()->json($lg);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $league_group)
    {
        $validated = $request->validate([
            'move_up' => 'sometimes|integer',
            'move_down' => 'sometimes|integer',
            'players_in_group' => 'sometimes|integer',
            'name' => 'sometimes',
            'order' => 'sometimes|integer',
            'points' => 'sometimes|integer',
            'points_loser' => 'sometimes|integer',
            'points_match' => 'sometimes|integer',
        ]);
        $league_group->update($validated);

        return response()->json($league_group);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $league_group)
    {
        $league_group->league->number_of_groups -= 1;
        $league_group->league->save();
        $league_group->delete();

        return response()->noContent();
    }
}
