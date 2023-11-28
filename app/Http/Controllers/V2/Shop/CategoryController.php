<?php

namespace App\Http\Controllers\V2\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = QueryBuilder::for(Category::class)
            ->allowedFilters([
                AllowedFilter::exact('club', 'club_id')->default(\request()->get('club')->id),
            ])->get();

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
            'club.id' => 'sometimes|exists:clubs,id',
            'image' => 'sometimes',
            'description' => 'sometimes',
        ]);

        if(isset($validated['club'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        } else {
            $validated['club_id'] = $request->get('club')->id;
        }
        $category = Category::create($validated);

        return JsonResource::make($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return JsonResource::make($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shop\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes',
            'club.id' => 'sometimes|exists:clubs,id',
            'image' => 'sometimes',
            'description' => 'sometimes',
        ]);

        if (isset($validated['club']['id'])) {
            $validated['club_id'] = $validated['club']['id'];
            unset($validated['club']);
        }

        $category->update($validated);

        return JsonResource::make($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shop\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }
}
