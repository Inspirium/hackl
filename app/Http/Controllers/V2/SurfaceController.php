<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\SurfaceCollection;
use App\Models\Surface;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SurfaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return SurfaceCollection
     */
    public function index()
    {
        $surfaces = QueryBuilder::for(Surface::class)
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return new SurfaceCollection($surfaces);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Surface  $surface
     * @return \Illuminate\Http\Response
     */
    public function show(Surface $surface)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Surface  $surface
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Surface $surface)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Surface  $surface
     * @return \Illuminate\Http\Response
     */
    public function destroy(Surface $surface)
    {
        $surface->delete();

        return response()->noContent();
    }
}
