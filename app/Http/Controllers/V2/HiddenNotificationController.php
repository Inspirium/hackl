<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HiddenNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(Request $request)
    {
        $user = $request->user();
        $hn = $user->hidden_notifications ?? [];
        $hn[$request->input('notification')] = true;
        $user->hidden_notifications = $hn;
        $user->save();

        return response()->json([
            'message' => 'Notifications hidden',
        ]);
    }
}
