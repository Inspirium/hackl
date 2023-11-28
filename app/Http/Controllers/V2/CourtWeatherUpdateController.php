<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\CourtWeatherUpdate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CourtWeatherUpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $updates = QueryBuilder::for(CourtWeatherUpdate::class)
            ->allowedFilters([
                AllowedFilter::exact('court', 'court_id'),
                AllowedFilter::exact('created_by'),
                AllowedFilter::callback('club', function ($query, $value) {
                    $query->whereHas('court.club', function ($query) use ($value) {
                        $query->where('id', $value);
                    });
                }),
            ])
            ->allowedIncludes(['court', 'createdBy'])
            ->allowedSorts(['created_at'])
            ->paginate(request()->input('limit', 25))
            ->appends(request()->query());
        return JsonResource::collection($updates);
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
    public function show(CourtWeatherUpdate $courtWeatherUpdate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourtWeatherUpdate $courtWeatherUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourtWeatherUpdate $courtWeatherUpdate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourtWeatherUpdate $courtWeatherUpdate)
    {
        //
    }
}
