<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\TournamentRoundResource;
use App\Models\TournamentRound;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Includes\IncludeInterface;
use Spatie\QueryBuilder\QueryBuilder;

class TournamentRoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TournamentRound  $tournamentRound
     * @return \Illuminate\Http\Response
     */
    public function show($tournamentRound)
    {
        $tournament = QueryBuilder::for(TournamentRound::where('id', $tournamentRound))
            ->allowedIncludes([
                'games', 'games.reservation',
            ])
            ->first();

        return new TournamentRoundResource($tournament);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TournamentRound  $tournamentRound
     * @return \Illuminate\Http\Response
     */
    public function edit(TournamentRound $tournamentRound)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TournamentRound  $tournamentRound
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TournamentRound $tournamentRound)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TournamentRound  $tournamentRound
     * @return \Illuminate\Http\Response
     */
    public function destroy(TournamentRound $tournamentRound)
    {
        //
    }
}

class ReservationInclude implements IncludeInterface
{
    protected string $column;

    protected string $function;

    public function __construct()
    {
    }

    public function __invoke(Builder $query, string $relations)
    {
        $query->with('games.reservation');
    }
}
