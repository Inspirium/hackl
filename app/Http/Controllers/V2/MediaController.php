<?php

namespace App\Http\Controllers\V2;

use App\Actions\SaveImageAction;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MediaController extends Controller
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
        $media = QueryBuilder::for(Media::class)
            ->allowedFilters([
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::exact('user', 'user_id'),
            ])
            ->get();

        return JsonResource::collection($media);
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
            'title' => 'sometimes',
            'description' => 'sometimes',
            'media' => 'required|image64:jpeg,jpg,png',
        ]);

        $link = $saveImageAction->execute($validated['media'], 'club');
        $validated['link'] = url('storage/'.$link);
        $validated['user_id'] = \Auth::id();
        $validated['club_id'] = $request->get('club')->id;
        unset($validated['media']);
        $media = Media::create($validated);

        return JsonResource::make($media);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function show(Media $media)
    {
        return JsonResource::make($media);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $media)
    {
        $media->delete();

        return response()->noContent();
    }
}
