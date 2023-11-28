<?php

namespace App\Http\Controllers\Api\Super;

use App\Http\Controllers\Controller;
use App\Models\User;

class SuperController extends Controller
{
    public function getPlayers()
    {
        $players = User::all();

        return response()->json(['players' => $players]);
    }
}
