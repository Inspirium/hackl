<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Court;
use App\Models\Surface;
use App\Models\User;
use App\Models\WorkingHours;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getCourt(Court $court)
    {
        $court->load(['working_hours']);

        return response()->json($court);
    }

    public function getSurfaces()
    {
        $surfaces = Surface::all();

        return response()->json($surfaces);
    }

    public function putClub(Request $request, $id)
    {
        $club = Club::find($id);
        $club->email = $request->input('email');
        $club->name = $request->input('name');
        $club->phone = $request->input('phone');
        $club->description = $request->input('description');
        $club->address = $request->input('address');
        $club->city = $request->input('city');
        $club->save();

        return response()->json($club);
    }

    public function postCourt(Request $request)
    {
        $court = new Court();
        $court->name = $request->input('name');
        $court->is_active = $request->input('is_active');
        $court->type = $request->input('type');
        $court->lights = $request->input('lights');
        $court->reservation_duration = $request->input('reservation_duration');
        $court->reservation_confirmation = ' ';

        $court->surface()->associate(Surface::find($request->input('surface.id')));

        $court->club()->associate($request->get('club'));

        $court->save();

        return response()->json($court);
    }

    public function putCourt(Request $request, Court $court)
    {
        $court->name = $request->input('name');
        $court->is_active = $request->input('is_active');
        $court->working_from = $request->input('working_from');
        $court->working_to = $request->input('working_to');
        $court->type = $request->input('type');
        $court->lights = $request->input('lights');
        $court->reservation_duration = $request->input('reservation_duration');
        $court->reservation_confirmation = ' ';

        $court->surface()->associate(Surface::find($request->input('surface_id')));

        $court->club()->associate($request->get('club'));

        $court->save();

        return response()->json($court);
    }

    public function deleteCourt(Court $court)
    {
        $court->delete();

        return response()->json();
    }

    public function getPlayers(Request $request, $type)
    {
        $request->get('club')->load([
            'players' => function ($query) {
                $query->orderBy('status', 'asc');
            },
        ]);

        $players = $request->get('club')->players;

        return response()->json($players);
    }

    public function putPlayer(Request $request, User $player)
    {
        if ($request->input('action') === 'delete') {
            $request->get('club')->players()->detach($player->id);

            return response()->json();
        }
        $action = false;
        switch ($request->input('action')) {
            case 'make_admin':
                $action = 'admin';
                break;
            case 'remove_admin':
            case 'approve':
            case 'unblock':
                $action = 'member';
                break;
            case 'block':
                $action = 'blocked';
                break;
        }
        if ($action) {
            $request->get('club')->players()->updateExistingPivot($player->id, ['status' => $action]);
        }

        return response()->json($action);
    }



    public function postHours(Request $request, Court $court)
    {
        $days = $request->input('days');
        $hours = $request->input('hours');
        $price = $request->input('price');
        $membership = $request->input('membership_id');
        sort($days);
        if (count($days) === 7) {
            $days = '*';
        } else {
            $days = implode(',', $days);
        }
        sort($hours);
        if (count($hours) === 24) {
            $hours = '*';
        } else {
            $hours = $hours[0].'-'.($hours[count($hours) - 1] + 1);
        }
        $cron = '* '.$hours.' * * '.$days;
        $h = new WorkingHours();
        $h->cron = $cron;
        $h->working = 1;
        $h->active_from = Carbon::now();
        $h->active_to = Carbon::now()->addYear(10);
        $h->court_id = $court->id;
        $h->price = $price;
        $h->membership_id = $membership;
        $h->save();

        return response()->json($h);
    }

    public function deleteHours(Court $court, WorkingHours $hours)
    {
        $hours->delete();
    }
}
