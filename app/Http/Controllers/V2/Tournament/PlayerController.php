<?php

namespace App\Http\Controllers\V2\Tournament;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tournament)
    {
        $players = QueryBuilder::for(Tournament::where('id', $tournament)->players())
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return UserCollection::make($players);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tournament $tournament)
    {
        //
    }

    public function show(Tournament $tournament, User $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tournament $tournament, User $player)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament, User $player)
    {
        return response()->noContent();
    }
}
