<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\PlayerProfile;
use App\Notifications\NewProfileData;
use Beste\Json;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PlayerProfileController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = QueryBuilder::for(PlayerProfile::class)
            ->allowedIncludes(['player', 'owner'])
            ->allowedFilters(
                AllowedFilter::exact('player', 'player_id'),
                AllowedFilter::exact('owner', 'owner_id'),
            )
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($profiles);
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
            'player.id' => 'required|integer',
            'data' => 'required'
        ]);

        $validated['owner_id'] = \Auth::id();
        $validated['player_id'] = $validated['player']['id'];
        unset($validated['player']);
        $profile = PlayerProfile::firstOrCreate([
            'player_id' => $validated['player_id'],
            'owner_id' => $validated['owner_id'],
        ]);
        $profile->update(['data' => $validated['data']]);
        //Notification::send($profile->player, new NewProfileData($profile));
        $profile->player->notify((new NewProfileData($profile))->locale($profile->player->lang));
        return JsonResource::make($profile);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PlayerProfile  $playerProfile
     * @return \Illuminate\Http\Response
     */
    public function show(PlayerProfile $playerProfile)
    {
        return JsonResource::make($playerProfile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PlayerProfile  $playerProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PlayerProfile $playerProfile)
    {
        $validated = $request->validate([
            'data' => 'required'
        ]);

        $playerProfile->update($validated);
        //Notification::send($playerProfile->player, new NewProfileData($playerProfile));
        $playerProfile->player->notify((new NewProfileData($playerProfile))->locale($playerProfile->player->lang));
        return JsonResource::make($playerProfile);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PlayerProfile  $playerProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlayerProfile $playerProfile)
    {

    }
}
