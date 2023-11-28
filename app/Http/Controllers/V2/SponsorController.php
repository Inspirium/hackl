<?php

namespace App\Http\Controllers\V2;

use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sponsors = QueryBuilder::for(Sponsor::class)
            ->allowedFilters([
                'title',
                AllowedFilter::exact('club', 'club_id'),
                ])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($sponsors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'sometimes|nullable',
            'link' => 'sometimes|nullable',
            'image' => 'sometimes|nullable|image64:jpg,jpeg,png',
            'status' => 'sometimes|in:draft,publish',
            'user_id' => 'sometimes|integer',
            'club_id' => 'sometimes|integer',
        ]);

        if (! isset($validated['club_id']) || ! $validated['club_id']) {
            $validated['club_id'] = $request->get('club')->id;
        }

        if (isset($validated['image']) && $validated['image']) {
            $validated['image'] = $this->saveImage($validated['image'], 'news');
        } else {
            unset($validated['image']);
        }

        $sponsor = Sponsor::create($validated);
        return new JsonResource($sponsor);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sponsor $sponsor)
    {
        return new JsonResource($sponsor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'title' => 'sometimes',
            'content' => 'sometimes|nullable',
            'link' => 'sometimes',
            'new_image' => 'sometimes|nullable|image64:jpg,jpeg,png',
            'status' => 'sometimes|in:draft,publish',
            'user_id' => 'sometimes|integer',
            'club_id' => 'sometimes|integer',
        ]);

        if (isset($validated['new_image']) && $validated['new_image']) {
            $validated['image'] = $this->saveImage($validated['new_image'], 'news');
            unset($validated['new_image']);
        }

        $sponsor->update($validated);
        return new JsonResource($sponsor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sponsor $sponsor)
    {
        $sponsor->delete();
        return response()->json([
            'message' => 'Sponsor deleted',
        ]);
    }
}
