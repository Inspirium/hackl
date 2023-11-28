<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\TaxClass;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaxClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = QueryBuilder::for(TaxClass::class)
            ->allowedFilters(['name', 'rate',
                AllowedFilter::exact('country', 'country_id'),
            ])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($classes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'rate' => 'required|numeric',
            'active_from' => 'sometimes|date',
            'active_to' => 'sometimes|date',
            'country_id' => 'required|exists:countries,id',
        ]);

        $class = TaxClass::create($validated);

        return JsonResource::make($class);
    }

    /**
     * Display the specified resource.
     */
    public function show(TaxClass $taxClass)
    {
        return JsonResource::make($taxClass);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaxClass $taxClass)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'rate' => 'sometimes|numeric',
            'active_from' => 'sometimes|date',
            'active_to' => 'sometimes|date',
            'country_id' => 'sometimes|exists:countries,id',
        ]);

        $taxClass->update($validated);

        return JsonResource::make($taxClass);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaxClass $taxClass)
    {
        //
    }
}
