<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Doctrine\DBAL\Query;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $competitions = QueryBuilder::for(Competition::class)
            ->allowedFilters([
                AllowedFilter::exact('club', 'club_id'),
            ])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());
        return JsonResource::collection($competitions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Competition $competition)
    {
        $competition->load('teams');
        return JsonResource::make($competition);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competition $competition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Competition $competition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competition $competition)
    {
        //
    }

    public function teams(Competition $competition)
    {
        $teams = QueryBuilder::for($competition->teams()->getQuery())
            ->orderBy('points', 'desc')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());
        return JsonResource::make($teams);
    }
}
