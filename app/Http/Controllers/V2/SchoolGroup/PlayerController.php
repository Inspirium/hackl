<?php

namespace App\Http\Controllers\V2\SchoolGroup;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolGroupResource;
use App\Http\Resources\UserCollection;
use App\Models\SchoolGroup;
use App\Models\Team;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PlayerController extends Controller
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
    public function index(SchoolGroup $schoolGroup)
    {
        $players = QueryBuilder::for($schoolGroup->players())
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return UserCollection::make($players);
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
        /*if ($request->has('players')) {
            $players = $request->input('players');
            $ids = [];
            foreach ($players as $player) {
                if (is_array($player) && isset($player['id'])) {
                    $ids[] = $player['id'];
                } elseif (intval($player)) {
                    $ids[] = $player;
                }
            }
            $schoolGroup->players()->syncWithoutDetaching($ids);
            forea
            $schoolGroup->thread->players()->syncWithoutDetaching($ids);
        }*/
        // if we only attach a single user object
        if ($request->has('id')) {
            $id = intval($request->input('id'));
            if ($id) {
                $schoolGroup->players()->syncWithoutDetaching([$id]);
                $team = Team::find($id);
                foreach ($team->players as $player) {
                    $schoolGroup->thread->players()->syncWithoutDetaching([$player->id]);
                }
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
        $schoolGroup->players()->detach($id);
        $team = Team::find($id);
        foreach ($team->players as $player) {
            $schoolGroup->thread->players()->detach($player->id);
        }

        return response()->noContent();
    }
}
