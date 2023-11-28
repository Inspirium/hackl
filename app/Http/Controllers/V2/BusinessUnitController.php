<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BusinessUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = QueryBuilder::for(BusinessUnit::class)
            ->allowedFilters(['name', 'is_active',
                AllowedFilter::exact('club', 'club_id'),
            ])
            ->allowedIncludes(['club', 'operator'])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($units);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'is_active' => 'sometimes|boolean',
            'is_default' => 'sometimes|boolean',
            'business_data' => 'sometimes|array',
            'invoice_number_structure' => 'sometimes|string',
            'operator.id' => 'sometimes|exists:users,id',
            'notes' => 'sometimes',
            'oparator_oib' => 'sometimes',
        ]);
        $club = request()->get('club');
        $validated['club_id'] = $club->id;
        if (isset($validated['operator']['id'])) {
            $validated['operator_id'] = $validated['operator']['id'];
            unset($validated['operator']);
        }
        $unit = BusinessUnit::create($validated);

        return JsonResource::make($unit);
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessUnit $businessUnit)
    {
        $businessUnit->load(['club', 'operator']);
        return JsonResource::make($businessUnit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessUnit $businessUnit)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'is_default' => 'sometimes|boolean',
            'business_data' => 'sometimes|array',
            'invoice_number_structure' => 'sometimes',
            'notes' => 'sometimes'
        ]);

        $businessUnit->update($validated);

        return JsonResource::make($businessUnit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BusinessUnit $businessUnit)
    {
        $businessUnit->delete();

        return response()->noContent();
    }
}
