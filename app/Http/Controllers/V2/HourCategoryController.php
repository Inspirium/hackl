<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\HourCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class HourCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = QueryBuilder::for(HourCategory::class)
            ->allowedFilters([
                'name',
                AllowedFilter::exact('club', 'club_id')->default(\request()->get('club')->id),
            ])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());
        return JsonResource::collection($categories);
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
            'name' => 'required',
            'description' => 'sometimes|nullable|string',
            'club.id' => 'required|exists:clubs,id',
            'color' => 'sometimes|nullable|string',
            'is_paid' => 'sometimes|nullable|boolean',
            'is_attractive' => 'sometimes|nullable|boolean',
            // 'type' => 'sometimes|nullable|array',
        ]);

        if (isset($validated['club']['id'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        }
        if (!isset($validated['club_id'])) {
            $validated['club_id'] = $request->get('club')->id;
        }

        $category = HourCategory::create($validated);
        return new JsonResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HourCategory  $hourCategory
     * @return \Illuminate\Http\Response
     */
    public function show(HourCategory $hoursCategory)
    {
        return new JsonResource($hoursCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HourCategory  $hourCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HourCategory $hoursCategory)
    {
        $validated = $request->validate([
            'name' => 'sometimes|nullable',
            'description' => 'sometimes|nullable|string',
            'club.id' => 'sometimes|nullable|exists:clubs,id',
            'color' => 'sometimes|nullable|string',
            'is_paid' => 'sometimes|nullable|boolean',
            'is_attractive' => 'sometimes|nullable|boolean',
            // 'type' => 'sometimes|nullable|array',
        ]);

        if (isset($validated['club']['id'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        }

        $hoursCategory->update($validated);
        return new JsonResource($hoursCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HourCategory  $hourCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(HourCategory $hoursCategory)
    {
        $hoursCategory->delete();
        return response()->noContent();
    }
}
