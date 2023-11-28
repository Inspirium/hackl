<?php

namespace App\Http\Controllers\V2;

use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $player = Auth::user();
        $notificationTypes = NotificationType::options();
        $out = [];
        foreach ($notificationTypes as $key => $value) {
            $out[] = [
                'key' => $key,
                'title' => __('notifications.' . $value),
                'value' => $player->notifications_settings[$key] ?? true,
            ];
        }

        return response()->json($out);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $player = Auth::user();

        $notificationTypes = NotificationType::options();
        $out = [];
        foreach ($notificationTypes as $key => $value) {
            $out[$key] = $player->notifications_settings[$key] ?? true;
        }

        $key = $request->input('key');
        $out[$key] = ! $out[$key];
        $player->notifications_settings = $out;
        $player->save();

        return response()->json($out);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
