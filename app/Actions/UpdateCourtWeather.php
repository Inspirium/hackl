<?php

namespace App\Actions;

use App\Models\Court;
use Illuminate\Support\Facades\Auth;

class UpdateCourtWeather
{
    public function start(Court $court, $from, $note, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        $court->weatherUpdates()->create([
            'from' => $from,
            'note' => $note,
            'created_by' => $user->id,
        ]);
    }

    public function finish(Court $court, $to, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
	    }
        $court->weatherUpdates()->whereNull('to')->update([
            'to' => $to,
        ]);
    }
}
