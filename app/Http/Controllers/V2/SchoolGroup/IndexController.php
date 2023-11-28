<?php

namespace App\Http\Controllers\V2\SchoolGroup;

use App\Actions\SaveImageAction;
use App\Http\Resources\SchoolGroupCollection;
use App\Http\Resources\SchoolGroupResource;
use App\Models\Location;
use App\Models\SchoolGroup;
use App\Models\Thread;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

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
        $groups = QueryBuilder::for(SchoolGroup::class)
            ->allowedFilters([
                'name',
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::callback('trainer', function($query, $value) {
                    $query->whereHas('trainers', function($query) use ($value) {
                        $query->where('user_id', $value);
                    });
                }),
                AllowedFilter::callback('player', function($query, $value) {
                    $query->whereHas('players', function($query) use ($value) {
                        $query->whereHas('players', function($query) use ($value) {
                            $query->where('users.id', $value);
                        });
                    });
                }),
            ])
            ->allowedIncludes([
                'players', 'trainers', 'locations', 'locations.club'
            ])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return SchoolGroupCollection::make($groups);
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
            'image' => 'sometimes|nullable|image64:jpg,png,jpeg',
            'trainer' => 'sometimes',
        ]);
        $validated['club_id'] = $request->get('club')->id;
        if (isset($validated['trainer'])) {
            if (is_array($validated['trainer']) && isset($validated['trainer']['id'])) {
                $validated['trainer_id'] = $validated['trainer']['id'];
            } else {
                $validated['trainer_id'] = $validated['trainer'];
            }
            unset($validated['trainer']);
        } else {
            $validated['trainer_id'] = \Auth::id();
        }

        if (isset($validated['image'])) {
            $validated['image'] = $saveImageAction->execute($validated['image'], 'school_group');
        }

        $group = SchoolGroup::create($validated);
        $group->trainers()->attach($validated['trainer_id']);
        if ($request->has('trainers')) {
            foreach ($request->input('trainers') as $user) {
                $group->trainers()->attach($user['id']);
                // TODO: send notification
            }
        }

        if ($request->has('players')) {
            foreach ($request->input('players') as $user) {
                $group->players()->attach($user['id']);
                // TODO: send notification
            }
        }
        $group->load(['players', 'trainers']);
        //create thread
        $thread = Thread::create(['title' => $group->name]);
        $group->thread()->associate($thread);
        $group->save();
        if ($request->input('locations')) {
            foreach ($request->input('locations') as $location) {
                $group->locations()->attach($location['id']);
            }
        }
        foreach ($group->players as $teams) {
            foreach ($teams->players as $player) {
                $thread->players()->attach($player->id);
            }
        }
        $ids = collect($group->trainers)->map(function ($item) {
        return $item['id'];
        });
        $thread->players()->attach($ids);

        return SchoolGroupResource::make($group);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolGroup  $schoolGroup
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolGroup $schoolGroup)
    {
        $schoolGroup->load(['players', 'trainers', 'trainer', 'locations', 'players.players.parents']);
        /**$schoolGroup = QueryBuilder::for(SchoolGroup::class)
            ->allowedIncludes([
                'players', 'trainers', 'locations', 'locations.club', 'players.parents',
                'players.subscriptions'
            ])
            ->find($schoolGroup);*/

        return SchoolGroupResource::make($schoolGroup);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolGroup  $schoolGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolGroup $schoolGroup, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'name' => 'required',
            'new_image' => 'sometimes|image64:jpg,png,jpeg',
            'trainer' => 'sometimes|integer',
        ]);
        if (isset($validated['image'])) {
            $validated['image'] = $saveImageAction->execute($validated['image'], 'school_group');
        }
        if (isset($validated['trainer'])) {
            if (is_array($validated['trainer']) && isset($validated['trainer']['id'])) {
                $validated['trainer_id'] = $validated['trainer']['id'];
            } else {
                $validated['trainer_id'] = $validated['trainer'];
            }
            unset($validated['trainer']);
        }
        $schoolGroup->update($validated);
        $schoolGroup->load(['players', 'trainers']);

        if ($request->input('locations')) {
            foreach ($request->input('locations') as $location) {
                $schoolGroup->locations()->attach($location['id']);
            }
        } else {
            $schoolGroup->locations()->detach();
        }


        return SchoolGroupResource::make($schoolGroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolGroup  $schoolGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolGroup $schoolGroup)
    {
        $schoolGroup->delete();

        return response()->noContent();
    }
}
