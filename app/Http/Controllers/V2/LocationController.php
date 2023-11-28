<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LocationController extends Controller
{
    public function index() {
        $locations = QueryBuilder::for(Location::class)
            ->allowedFilters(['name', 'description',
                AllowedFilter::exact('club', 'club_id')
            ])
            ->allowedSorts(['name'])
            ->allowedIncludes(['club'])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($locations);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'club.id' => 'sometimes|exists:clubs,id',
        ]);

        if (isset($validated['club']['id'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        } else {
            $validated['club_id'] = $request->get('club')->id;
        }

        $location = Location::create($validated);

        return JsonResource::make($location);
    }

    public function show(Location $location) {
        return JsonResource::make($location);
    }

    public function update(Request $request, Location $location) {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'club.id' => 'sometimes|exists:clubs,id',
        ]);

        if (isset($validated['club']['id'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        }

        $location->update($validated);

        return JsonResource::make($location);
    }

    public function destroy(Location $location) {
        $location->delete();
        return response()->noContent();
    }
}
