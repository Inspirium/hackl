<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\CourtCollection;
use App\Http\Resources\CourtResource;
use App\Models\Court;
use App\Models\Surface;
use App\Notifications\WeatherUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CourtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return CourtCollection
     */
    public function index(Request $request)
    {
        $date = request()->input('date');
        if (! $date) {
            $date = date('Y-m-d');
        }
        $courts = QueryBuilder::for(Court::class)
            ->allowedFilters(['is_active',
                AllowedFilter::exact('club_id')->ignore(0, 26)->default($request->get('club')->id),
                'show_on_tenisplus', 'invalid', 'wifi', 'airconditioner', 'heating'
            ])
            ->allowedSorts(['order', AllowedSort::field('club', 'club_id'),
                AllowedSort::callback('distance', function ($query, $direction) use ($request) {
                    $direction = $direction ? 'DESC' : 'ASC';
                    $query->join('clubs', 'courts.club_id', '=', 'clubs.id')->orderByRaw("ST_Distance_Sphere(point(clubs.longitude, clubs.latitude), point(?, ?)) $direction", [
                        $request->input('longitude'),
                        $request->input('latitude'),
                    ]);
                }),
                ])->defaultSort('order')
            ->allowedIncludes(['club', 'sports'])
            ->with([
                'reservations' => function ($query) use ($date) {
                    $query->whereDate('from', $date);
                },
            ], 'surface')
            ->select('courts.*')
            ->paginate(request()->input('limit', 20))
            ->appends(request()->query());
        return CourtCollection::make($courts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!\Auth::user()->is_admin->contains($request->get('club')->id)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validated = $request->validate([
            'name' => 'required',
            'is_active' => 'required|boolean',
            'type' => 'required|in:open,closed',
            'lights' => 'required|boolean',
            'reservation_duration' => 'required',
            'reservation_confirmation' => 'required|boolean',
            'reservation_hole' => 'sometimes|boolean',
            'hero_image' => 'sometimes|url',
            'show_on_tenisplus' => 'sometimes|boolean',
            'invalid' => 'sometimes|nullable|boolean',
            'airconditioner' => 'sometimes|nullable|boolean',
            'wifi' => 'sometimes|nullable|boolean',
            'size' => 'sometimes|nullable|integer',
            'heating' => 'sometimes|nullable|boolean',
            'description' => 'sometimes|nullable',
        ]);

        $court = Court::create($validated);

        $court->surface()->associate(Surface::find($request->input('surface.id')));

        $court->club()->associate($request->get('club'));

        if ($request->input('sports')) {
            if ($request->input('sports')) {
                $sports = collect($request->input('sports'))->map(function ($i) {
                    if (is_int($i)){
                        return $i;
                    }
                    return $i['id'];
                });
                $court->sports()->sync($sports);
            }
        }

        $court->save();

        return CourtResource::make($court);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Court  $court
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Court $court)
    {
        if ($request->has('date') && $request->input('date')) {
            $date = $request->input('date');
            $court->load([
                'reservations' => function ($query) use ($date) {
                    $query->whereDate('from', $date);
                },
                'working_hours' => function ($query) use ($date) {
                    $query->whereDate('active_from', '>', $date);
                    $query->whereDate('active_to', '<', $date);
		},
		'sports'
            ]);
        }

        return new CourtResource($court);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Court  $court
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Court $court)
    {
        if (!\Auth::user()->is_admin->contains($request->get('club')->id)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $validated = $request->validate([
            'name' => 'required',
            'is_active' => 'required|boolean',
            'type' => 'required|in:open,closed',
            'lights' => 'required|boolean',
            'reservation_duration' => 'required',
            'reservation_confirmation' => 'required|boolean',
            'reservation_hole' => 'sometimes|boolean',
            'hero_image' => 'sometimes|url',
            'show_on_tenisplus' => 'sometimes|boolean',
            'member_reservation' => 'sometimes|boolean',
            'weather' => 'sometimes|nullable|boolean',
            'weather_message' => 'sometimes|nullable',
            'court_break_from' => 'sometimes|nullable',
            'court_break_to' => 'sometimes|nullable',
            'invalid' => 'sometimes|nullable|boolean',
            'airconditioner' => 'sometimes|nullable|boolean',
            'wifi' => 'sometimes|nullable|boolean',
            'size' => 'sometimes|nullable|integer',
            'heating' => 'sometimes|nullable|boolean',
            'description' => 'sometimes|nullable',
        ]);

        if ($request->input('surface.id')) {
            $validated['surface_id'] = $request->input('surface.id');
        }

        $court->update($validated);

        if ($request->input('sports')) {
            $sports = collect($request->input('sports'))->map(function ($i) {
                return $i['id'];
            });
            $court->sports()->sync($sports);
        }

        if (isset($validated['weather']) && in_array((int)$validated['weather'], [0, 1], true)) {
            $players = [];

            $p = $court->setWeather($validated['weather'], $validated['weather_message']??'');
            $players = array_merge($players, $p);

            $players = array_unique($players);
            if ($players) {
                // \Notification::send($players, new WeatherUpdate(Auth::user(), $validated['weather_message']??''));
                foreach ($players as $player) {
                    $player->notify((new WeatherUpdate(Auth::user(), $validated['weather_message']??''))->locale($player->lang));
                }
            }
            $validated['weather'] = $validated['weather'] === 'off' ? 0 : 1;
            unset($validated['weather_message']);
        }

        //$court->load('surface');
        return CourtResource::make($court);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Court  $court
     * @return \Illuminate\Http\Response
     */
    public function destroy(Court $court)
    {
        $court->reservations()->where('from', '>=', date('Y-m-d'))->delete();
        $court->delete();

        return response()->noContent();
    }

    public function order(Request $request)
    {
        $order_array = $request->input('order');
        foreach ($order_array as $order => $court_id) {
            Court::find($court_id)->update(['order' => intval($order)]);
        }
    }
}
