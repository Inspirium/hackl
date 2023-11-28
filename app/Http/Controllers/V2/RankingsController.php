<?php

namespace App\Http\Controllers\V2;

use App\Http\Resources\TeamResource;
use App\Http\Resources\UserCollection;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function my_club_rankings(Request $request)
    {
        $user = \Auth::user();
        $user_team = Team::ofSingleUser($user->id)->first();
        if (!$user_team) {
            return response()->json(['message' => 'You are not in a team'], 400);
        }
        $rank = $user_team->rank_club;
        $club = $request->get('club')->id;
        if ($rank) {
            $players = [];
            $range = [max($rank - 2, 1), $rank - 2 < 0 ? 5 : $rank + 2];
            for ($i = $range[0]; $i <= $range[1]; $i++) {
                $players[$i] = 0;
                if ($i === $rank) {
                    $players[$i] = [$user_team->id];
                }
            }
            $p = DB::table('club_team')
                ->where('club_id', $club)->whereBetween('rank', $range)
                ->get();
            foreach ($p as $item) {
                if (in_array($item->team_id, $players)) {
                    continue;
                }
                if ($players[$item->rank]) {
                    continue;
                }
                $players[$item->rank] = $item->team_id;
            }
            /*$out = [$user_team->id];
            $k = 0;
            for ($j = 1; $j < 4; $j++) {
                if ($rank-$j < 0) {
                    continue;
                }
                if ($players[$rank-$j]) {
                    $out[] = $players[$rank-$j];
                    $k++;
                    if ($k == 2) {
                        break;
                    }
                }

            }
            $k = 0;
            for ($j = 1; $j < 4; $j++) {
                if ($players[$rank+$j]) {
                    $out[] = $players[$rank+$j];
                    $k++;
                    if ($k == 2) {
                        break;
                    }
                }
            }
            $players = $out;*/
        } else {
            $players = DB::table('club_team')
                ->where('club_id', $club)->whereBetween('rank', [1, 5])
                ->limit(5)
                ->pluck('team_id');
        }

        //return response()->json($players);
        $players = Team::find($players);

        return TeamResource::collection($players);
    }
}
