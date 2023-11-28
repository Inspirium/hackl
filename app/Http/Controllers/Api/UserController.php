<?php
/**
 * Filename: UserController.php
 *
 * User: mbanusic
 * Date: 06/04/2018
 * Time: 23:24
 *
 * Contact: http://mbanusic.com
 * License: GPL-2.0+
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getResults()
    {
        $this->authorize('view', Auth::user());
        $my = Result::whereHas('players', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['players', 'court'])->limit(10)->get();

        return response()->json(['results' => $my]);
    }

    public function getPlayers()
    {
    }

    public function getUser()
    {
        $user = Auth::user();
        $user->makeVisible(['club_member']);

        return response()->json($user);
    }
}
