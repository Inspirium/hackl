<?php

namespace App\Http\Controllers\V2\SchoolGroup;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolGroupResource;
use App\Http\Resources\UserCollection;
use App\Models\SchoolGroup;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index(Request $request, SchoolGroup $schoolGroup)
    {
        $trainers = QueryBuilder::for($schoolGroup->trainers())
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return UserCollection::make($trainers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return SchoolGroupResource
     */
    public function store(Request $request, SchoolGroup $schoolGroup)
    {
        // handle if multiple players have been sent
        if ($request->has('trainers')) {
            $players = $request->input('players');
            $ids = [];
            foreach ($players as $player) {
                if (is_array($player) && isset($player['id'])) {
                    $ids[] = $player['id'];
                } elseif (intval($player)) {
                    $ids[] = $player;
                }
            }
            $schoolGroup->trainers()->syncWithoutDetaching($ids);
        }
        // if we only attach a single user object
        if ($request->has('id')) {
            $id = intval($request->input('id'));
            if ($id) {
                $schoolGroup->trainers()->syncWithoutDetaching([$id]);
            }
        }
        $schoolGroup->load(['players', 'trainers']);

        return SchoolGroupResource::make($schoolGroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolGroup $schoolGroup, $id)
    {
        $schoolGroup->trainers()->detach($id);

        return response()->noContent();
    }
}
