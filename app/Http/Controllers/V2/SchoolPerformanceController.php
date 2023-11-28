<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\SchoolPerformance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SchoolPerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $performances = QueryBuilder::for(SchoolPerformance::class)
            ->allowedFilters([
                AllowedFilter::exact('player', 'player_id'),
                AllowedFilter::exact('school_group', 'school_group_id'),
                AllowedFilter::exact('trainer', 'trainer_id'),
                AllowedFilter::exact('reservation', 'reservation_id'),
            ])
            ->allowedIncludes([
                'player',
                'school_group',
                'trainer',
                'reservation',
            ])
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return JsonResource::collection($performances);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'player.id' => 'required|exists:users,id',
            'trainer.id' => 'required|exists:users,id',
            'school_group.id' => 'required|exists:school_groups,id',
            'reservation.id' => 'required|exists:reservations,id',
            'data' => 'required|array',
        ]);

        $validated['player_id'] = $validated['player']['id'];
        $validated['trainer_id'] = $validated['trainer']['id'];
        $validated['school_group_id'] = $validated['school_group']['id'];
        $validated['reservation_id'] = $validated['reservation']['id'];
        unset($validated['player'], $validated['trainer'], $validated['school_group'], $validated['reservation']);

        $performance = SchoolPerformance::create($validated);

        return JsonResource::make($performance);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolPerformance  $schoolPerformance
     * @return \Illuminate\Http\Response
     */
    public function show($schoolPerformance)
    {
        $sp = QueryBuilder::for(SchoolPerformance::where('id', $schoolPerformance))
            ->allowedIncludes([
                'player',
                'school_group',
                'trainer',
                'reservation',
            ])
            ->firstOrFail();
        return JsonResource::make($sp);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolPerformance  $schoolPerformance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolPerformance $schoolPerformance)
    {
        $validated = $request->validate([
            'data' => 'required|array',
        ]);
        $schoolPerformance->update($validated);

        return JsonResource::make($schoolPerformance);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolPerformance  $schoolPerformance
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolPerformance $schoolPerformance)
    {
        $schoolPerformance->delete();

        return response()->noContent();
    }
}
