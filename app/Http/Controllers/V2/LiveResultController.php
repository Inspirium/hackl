<?php

namespace App\Http\Controllers\V2;

use App\Events\LiveResultUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\ResultResource;
use App\Models\Game;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LiveResultController extends Controller
{
    public function store(Request $request, Game $game)
    {
        /** @var Result $result */
        $result = $game->result;
        if (! $result) {
            $result = Result::create([
                'official' => true,
                'rated' => true,
                'date' => Carbon::now(),
                'verified' => false,
                'live' => true,
                'live_data' => $request->input('live_data'),
                'club_id' => $request->get('club')->id,
                'game_id' => $game->id,
                'sets' => $request->input('live_data.sets'),
            ]);
            $game->live = true;
            $game->save();
            $result->players()->attach($game->players->map(function ($p) {
            return $p->id;
            }));
        } else {
            $result->live_data = $request->input('live_data');
            $result->sets = $request->input('live_data.sets');
            $result->save();
            broadcast(new LiveResultUpdated($game));
        }

        return ResultResource::make($result);
    }

    public function show(Game $game)
    {
        return ResultResource::make($game->result);
    }

    public function update(Request $request, Game $game)
    {
        $game->live = $request->input('live') ?? null;
        $game->result->live = $request->input('live') ?? null;
        $game->result->live_data = $request->input('live_data') ?? null;
        if ($request->input('live_data')) {
            $game->result->sets = $request->input('live_data.sets');
        }
        $game->result->save();
        $game->save();
        broadcast(new LiveResultUpdated($game));

        return ResultResource::make($game->result);
    }
}
