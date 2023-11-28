<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\ClubCollection;
use App\Http\Resources\ClubResource;
use App\Models\Club;
use App\Notifications\WeatherUpdate;
use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\DNS;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ClubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'this');
    }

    /**
     * Display a listing of the resource.
     *
     * @return ClubCollection
     */
    public function index(Request $request)
    {
        $clubs = QueryBuilder::for(Club::class)
            ->allowedFilters(['name', 'type', 'is_active',
                AllowedFilter::exact('country', 'country_id')
            ])
            ->allowedIncludes(['memberships', 'courts', 'admins', 'country', 'tax_class', 'sports'])
            ->allowedSorts(
                AllowedSort::callback('distance', function ($query, bool $direction) use ($request) {
                    $direction = $direction ? 'DESC' : 'ASC';
                    $query->orderByRaw("ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) $direction", [
                        $request->input('longitude'),
                        $request->input('latitude'),
                    ]);
            }))
            ->paginate($request->input('limit', 25))
            ->appends($request->query());

        return new ClubCollection($clubs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return ClubResource
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'subdomain' => 'required',
            'domain' => 'sometimes|url',
            'description' => 'sometimes|nullable',
            'address' => 'sometimes|nullable',
            'postal_code' => 'sometimes|nullable',
            'city' => 'sometimes|nullable',
            'county' => 'sometimes|nullable',
            'region' => 'sometimes|nullable',
            'country.id' => 'sometimes|exists:countries,id',
            'phone' => 'sometimes|nullable',
            'fax' => 'sometimes|nullable',
            'email' => 'sometimes|email',
            'is_active' => 'boolean',
            'has_players' => 'boolean',
            'has_courts' => 'boolean',
            'latitude' => 'sometimes',
            'longitude' => 'sometimes',
            'logo' => 'sometimes|nullable|image64:jpeg,jpg,png',
            'validate_user' => 'sometimes|required|boolean',
            'owner' => 'sometimes|array',
            'type' => 'sometimes',
            'hide_personal_data' => 'sometimes|boolean',
            'hide_ranks' => 'sometimes|boolean',
            'social' => 'sometimes',
            'payment_online' => 'sometimes|boolean',
            'payment_accontation' => 'sometimes|boolean',
            'show_competitions' => 'sometimes|boolean',
            'is_w2p' => 'sometimes|boolean',
            'tax_class.id' => 'sometimes|exists:tax_classes,id',
            'business_data' => 'sometimes|nullable|array',
        ]);
        if (isset($validated['logo'])) {
            $validated['logo'] = $this->saveImage($validated['logo'], 'logos');
        }
        $owners = false;
        if (isset($validated['owner'])) {
            $owners = $validated['owner'];
            unset($validated['owner']);
        }
        if (isset($validated['country'])) {
            $validated['country_id'] = $validated['country']['id'];
            unset($validated['country']);
        } else {
            $validated['country_id'] = 1;
        }

        if (isset($validated['tax_class'])) {
            $validated['tax_class_id'] = $validated['tax_class']['id'];
            unset($validated['tax_class']);
        }
        $club = Club::create($validated);
         if ($owners) {
            foreach ($owners as $owner) {
                $club->admins()->attach($owner['id'], ['level' => 'owner']);
            }
        }
         $club->players()->attach(Auth::user()->id, ['status' => 'member']);
        if ($request->input('sports')) {
            $sports = collect($request->input('sports'))->map(function ($i) {
                return $i['id'];
            });
            $club->sports()->sync($sports);
        }
        return new ClubResource($club);
    }

    /**
     * Get current club
     *
     * @param  Request  $request
     * @return ClubResource
     */
    public function this(Request $request)
    {
        if ($request->get('club')) {
            $request->get('club')->load(['memberships', 'country', 'club_socials', 'sports']);

            return new ClubResource($request->get('club'));
        }
        return response()->noContent();
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Club  $club
     * @return ClubResource
     */
    public function show($club)
    {
        $club = QueryBuilder::for(Club::where('id', $club))
            ->allowedIncludes(['memberships', 'courts', 'admins', 'country', 'tax_class', 'club_socials', 'sports'])
            ->first();

        return new ClubResource($club);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Club  $club
     * @return ClubResource
     */
    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => 'sometimes',
            'subdomain' => 'sometimes',
            'description' => 'sometimes',
            'address' => 'sometimes',
            'postal_code' => 'sometimes',
            'city' => 'sometimes',
            'county' => 'sometimes',
            'region' => 'sometimes|nullable',
            'country.id' => 'sometimes|exists:countries,id',
            'phone' => 'sometimes|nullable',
            'fax' => 'sometimes|nullable',
            'email' => 'sometimes|nullable|email',
            'is_active' => 'sometimes|boolean',
            'has_players' => 'sometimes|boolean',
            'has_courts' => 'sometimes|boolean',
            'latitude' => 'sometimes',
            'longitude' => 'sometimes',
            'new_logo' => 'sometimes|nullable|image64:jpeg,jpg,png',
            'weather' => 'sometimes|nullable|in:0,1,open,closed,off,all',
            'weather_message' => 'sometimes|nullable',
            'validate_user' => 'sometimes',
            'type' => 'sometimes',
            'hide_personal_data' => 'sometimes',
            'hide_ranks' => 'sometimes',
            'hero_images' => 'sometimes',
            'social' => 'sometimes',
            'payment_online' => 'sometimes|boolean',
            'payment_accontation' => 'sometimes|boolean',
            'show_competitions' => 'sometimes|boolean',
            'is_w2p' => 'sometimes|boolean',
            'tax_class.id' => 'sometimes|exists:tax_classes,id',
            'business_data' => 'sometimes|array',
        ]);
        //update logo
        $logo = isset($validated['new_logo']) ? $validated['new_logo'] : false;
        unset($validated['new_logo']);
        if ($logo) {
            $validated['logo'] = $this->saveImage($logo, 'logos');
        }

        if (isset($validated['weather']) && ! in_array($validated['weather'], [0, 1], true)) {
            /*$club->load('courts');
            $players = [];
            foreach ($club->courts as $court) {
                $p = $court->setWeather($validated['weather'], $validated['weather_message']??'');
                $players = array_merge($players, $p);
            }
            $players = array_unique($players);
            if ($players) {
                foreach ($players as $player) {
                    $player->notify((new WeatherUpdate(Auth::user(), $validated['weather_message']??''))->locale($player->lang));
                }
                // \Notification::send($players, new WeatherUpdate(Auth::user(), $validated['weather_message']??''));
            }*/
            $validated['weather'] = $validated['weather'] === 'off' ? 0 : 1;
        }

        if (isset($validated['hero_images'])) {
            $images = [];
            foreach ($club->hero_images as $key => $image) {
                $images[$key] = isset($validated['hero_images'][$key]) && str_contains($validated['hero_images'][$key], 'https://') ? $validated['hero_images'][$key] : $image;
            }
            $validated['hero_images'] = $images;
        }
        if (isset($validated['tax_class'])) {
            $validated['tax_class_id'] = $validated['tax_class']['id'];
            unset($validated['tax_class']);
        }
        if (isset($validated['country'])) {
            $validated['country_id'] = $validated['country']['id'];
            unset($validated['country']);
        }
        $club->update($validated);
        if ($request->input('sports')) {
            $sports = collect($request->input('sports'))->map(function ($i) {
                return $i['id'];
            });
            $club->sports()->sync($sports);
        }
        return new ClubResource($club);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Club  $club
     * @return JsonResponse
     */
    public function destroy(Club $club)
    {
        try {
            foreach ($club->courts as $court) {
                $court->delete();
            }
            $club->delete();
        } catch (\Exception $exception) {
            return response()->json(['error' => 'Failed to delete']);
        }

        return response()->noContent();
    }
}
