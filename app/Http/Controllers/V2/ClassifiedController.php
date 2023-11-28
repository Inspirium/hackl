<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\ClassifiedCollection;
use App\Http\Resources\ClassifiedResource;
use App\Models\Classified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ClassifiedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return ClassifiedCollection
     */
    public function index()
    {
        $classifieds = QueryBuilder::for(Classified::class)
            ->allowedFilters([
                'category',
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('club', 'club_id'),
            ])
            ->allowedSorts([
                AllowedSort::field('date', 'created_at'),
                'title',
            ])->defaultSort('-created_at')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return new ClassifiedCollection($classifieds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return ClassifiedResource
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'sometimes|nullable|image64:jpg,jpeg,png',
            'price' => 'required',
            'category' => 'required',
            'user_id' => 'sometimes|nullable',
        ]);

        if ($validated['image']) {
            $validated['image'] = $this->saveImage($validated['image'], 'classifieds');
        }
        if (!$validated['image']) {
            $validated['image'] = ' ';
        }

        if (! isset($validated['user_id']) || ! $validated['user_id']) {
            $validated['user_id'] = Auth::id();
        }
        $validated['club_id'] = $request->get('club')->id;

        $classified = Classified::create($validated);

        return ClassifiedResource::make($classified);
    }

    /**
     * Display the specified resource.
     *
     * @param  Classified  $classified
     * @return ClassifiedResource
     */
    public function show(Classified $classified)
    {
        return new ClassifiedResource($classified);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Classified  $classified
     * @return ClassifiedResource
     */
    public function update(Request $request, Classified $classified)
    {
        $validated = $request->validate([
            'title' => 'sometimes',
            'description' => 'sometimes',
            'new_image' => 'sometimes|nullable|image64:jpeg,jpg,png',
            'price' => 'sometimes',
            'category' => 'sometimes',
            'user_id' => 'sometimes',
        ]);

        if (isset($validated['new_image']) && $validated['new_image']) {
            $validated['image'] = $this->saveImage($validated['new_image'], 'classifieds');
            unset($validated['new_image']);
        }

        $classified->update($validated);

        return new ClassifiedResource($classified);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Classified  $classified
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classified $classified)
    {
        $classified->delete();

        return response()->noContent();
    }
}
