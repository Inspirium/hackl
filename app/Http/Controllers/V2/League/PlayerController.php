<?php

namespace App\Http\Controllers\V2\League;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\League;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $league)
    {
        $players = QueryBuilder::for(League::where('id', $league)->players())
            ->allowedFilters([
                AllowedFilter::callback('admin', function ($query, $value) {
                    $query->wherePivot('admin', 1);
                }),
                AllowedFilter::callback('player', function ($query) {
                    $query->wherePivot('player', 1);
                }),
            ])
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return UserCollection::make($players);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, League $league)
    {
        $players = $request->input('players');
        if ($players) {
            foreach ($players as $player) {
                if (! $league->players->contains($player['id'])) {
                    $league->players()->attach([$player['id'] => ['player' => true]]);
                    if ($league->status == 4 && $league->classification === 'elo') {
                        $league->groups[0]->players()->attach([$player['id'] => ['player' => 1, 'score' => 1500]]);
                    }
                }
            }
        }
        $admins = $request->input('admins');
        if ($admins) {
            foreach ($admins as $player) {
                if (! $league->admins->contains($player['id'])) {
                    $league->admins()->attach([$player['id'] => ['admin' => true]]);
                }
            }
        }

        return UserCollection::make($league->players);
    }

    public function show(League $league, User $player)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, League $league, User $player)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, League $league, User $player)
    {
        $admin = $request->input('admin');
        $players = $request->input('player');
        if ($admin) {
            $league->admins()->detach($player->id);
        }
        if ($players) {
            $league->players()->detach($player->id);
        }
        if (! $admin && ! $players) {
            $league->admins()->detach($player->id);
            $league->players()->detach($player->id);
        }

        return response()->noContent();
    }
}
