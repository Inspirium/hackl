<?php

namespace App\Http\Controllers\V2;

use App\Actions\SaveImageAction;
use App\Http\Controllers\Controller;
use App\Models\ReservationCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReservationCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = QueryBuilder::for(ReservationCategory::class)
            ->allowedFilters(['name',
                AllowedFilter::exact('club', 'club_id')->default(\request()->get('club')->id)
            ])
            ->allowedSorts(['name'])
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
    public function store(Request $request, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'sometimes|nullable|string',
            'club.id' => 'required|exists:clubs,id',
            'color' => 'sometimes|nullable|string',
            'is_paid' => 'sometimes|nullable|boolean',
            'image' => 'sometimes|nullable',
        ]);

        if (isset($validated['club']['id'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        }
        if (!isset($validated['club_id'])) {
            $validated['club_id'] = $request->get('club')->id;
        }
        $image = false;
        if (isset($validated['image'])) {
            $image = $validated['image'];
            unset($validated['image']);
        }

        $category = ReservationCategory::create($validated);
        if ($image) {
            $category->images()->attach($image, ['main' => 1]);
        }
        return JsonResource::make($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReservationCategory  $reservationCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ReservationCategory $reservationCategory)
    {
        return JsonResource::make($reservationCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReservationCategory  $reservationCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationCategory $reservationCategory, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'sometimes|nullable|string',
            'club.id' => 'sometimes|exists:clubs,id',
            'color' => 'sometimes|nullable|string',
            'is_paid' => 'sometimes|nullable|boolean',
            'image' => 'sometimes|nullable',
        ]);

        if (isset($validated['club']['id'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        }

        $reservationCategory->update($validated);

        return JsonResource::make($reservationCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReservationCategory  $reservationCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationCategory $reservationCategory)
    {
        $reservationCategory->delete();
        return response()->noContent();
    }
}
