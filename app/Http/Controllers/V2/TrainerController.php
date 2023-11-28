<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\TrainerResource;
use App\Http\Resources\UserCollection;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $trainers = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('display_name', function ($query, $value) {
                    if ($value) {
                        $query->where('first_name', 'LIKE', '%'.$value.'%')->orWhere('last_name', 'LIKE', '%'.$value.'%');
                    }
                }),
                AllowedFilter::callback('available', function($query, $value) {
                    if ($value) {
                        $query->whereHas('trainer', function ($query) {
                            $query->where('available', 1);
                        });
                    }
                }),
                AllowedFilter::callback('club', function ($query, $value) {
                    if ($value) {
                        $query->whereHas('clubs', function ($query) use ($value) {
                            $query->where('clubs.id', $value);
                        });
                    }
                }),
            ])
            ->whereHas('trainer')
            ->with('trainer')
            ->allowedSorts([
                'last_name',
                AllowedSort::callback('distance', function ($query, $direction) use ($request) {
                    $direction = $direction ? 'DESC' : 'ASC';
                    $query
                        ->leftJoin('club_user', 'users.id', '=', 'club_user.player_id')
                        ->leftJoin('clubs', 'club_user.club_id', '=', 'clubs.id')
                        ->orderByRaw("ST_Distance_Sphere(point(clubs.longitude, clubs.latitude), point(?, ?)) $direction", [
                        $request->input('longitude'),
                        $request->input('latitude'),
                    ]);
                }),
            ])
            ->groupBy('users.id')
            ->select('users.*')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return UserCollection::make($trainers);
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
            'price' => 'numeric|sometimes',
            'court_included' => 'boolean|sometimes',
            'show_phone' => 'boolean|sometimes',
            'available' => 'boolean|sometimes',
            'description' => 'sometimes',
            'user_id' => 'required',
        ]);

        $trainer = User::findOrFail($validated['user_id']);
        unset($validated['user_id']);
        $trainer->trainer()->create($validated);

        return TrainerResource::make($trainer->trainer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Models\Trainer  $trainer
     * @return \Illuminate\Http\Response
     */
    public function show(Trainer $trainer)
    {
        return TrainerResource::make($trainer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Trainer  $trainer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'price' => 'numeric',
            'court_included' => 'boolean|sometimes',
            'show_phone' => 'boolean|sometimes',
            'available' => 'boolean|sometimes',
            'description' => 'sometimes',
        ]);
        $trainer->update($validated);

        return TrainerResource::make($trainer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Trainer  $trainer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trainer $trainer)
    {
        $trainer->delete();

        return response()->noContent();
    }
}
