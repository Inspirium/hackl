<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Team;
use App\Models\User;
use App\Notifications\ApplicationApproved;
use App\Notifications\NewApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ClubMemberController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request)
    {
        /** @var Club $club */
        $club = $request->input('club_id');
        if ($club) {
            $club = Club::find($club);
        } else {
            $club = $request->get('club');
        }

        $members = $request->input('member');
        $blocked = $request->input('blocked');
        $applicants = $request->input('applicant');
        $delete = $request->input('delete');
        $cashier = $request->input('cashier');
        if ($members) {
            foreach ($members as $member) {
                if ($club->players()->find($member['id'])) {
                    $is_applicant = DB::table('club_user')
                        ->where('player_id', $member['id'])
                        ->where('club_id', $club->id)
                        ->where('status', 'applicant')
                        ->first();
                    $club->players()->updateExistingPivot($member['id'], ['status' => 'member']);

                    if ($is_applicant) {
                        $user = User::find($member['id']);
                        $user->notify((new ApplicationApproved(\Auth::user(), $club))->locale($user->lang));
                    }
                } else {
                    $club->players()->attach($member['id'], ['status' => 'member']);
                }

                foreach (Team::query()->where('primary_contact_id',  $member['id'])->get() as $team) {
                    if (!$team->clubs()->where('club_team.club_id', $club->id)->first()) {
                        $team->clubs()->attach($club->id);
                    }
                }
                Cache::delete('club_member_'.$member['id']);
            }
        }
        if ($blocked) {
            foreach ($blocked as $member) {
                $message = $request->input('message');
                if ($club->players()->find($member['id'])) {
                    $club->players()->updateExistingPivot($member['id'], ['status' => 'blocked', 'messages' => ['blocked' => $message]]);
                } else {
                    $club->players()->attach($member['id'], ['status' => 'blocked', 'messages' => ['blocked' => $message]]);
                }
                Cache::delete('club_member_'.$member['id']);
            }
        }
        if ($applicants) {
            foreach ($applicants as $member) {
                if ($club->players()->find($member['id'])) {
                    $club->players()->updateExistingPivot($member['id'], ['status' => 'applicant']);
                } else {
                    $club->players()->attach($member['id'], ['status' => 'applicant']);
                    //Notification::send($request->get('club')->all_admins, new NewApplicant(User::find($member['id'])));
                    $u = User::find($member['id']);
                    foreach($club->all_admins as $admin) {
                        $admin->notify((new NewApplicant($u))->locale($admin->lang));
                    }
                }
                Cache::delete('club_member_'.$member['id']);
            }
        }
        if ($delete) {
            foreach ($delete as $member) {
                $club->players()->detach($member['id']);
                $teams = Team::query()->where('number_of_players', 1)->where('primary_contact_id',  $member['id'])->get();
                foreach ($teams as $team) {
                    $team->clubs()->detach($club->id);
                        foreach ($team->players as $player) {
                            $memberships = $player->memberships()->where('club_id', $club->id)->get();
                            if ($memberships->count()) {
                                $player->memberships()->detach($memberships->map(function ($m) {
                                    return $m->id;
                                })->toArray());
                            }
                        }
                }
            }
        }

        if ($cashier) {
            foreach ($cashier as $member) {
                $current = DB::table('club_user')->where('club_id', $club->id)->where('player_id', $member['id'])->first();
                $club->players()->updateExistingPivot($member['id'], ['cashier' => !$current->cashier]);
            }
        }

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
    }

    public function eloPoints(Request $request) {
        $player = $request->input('player');
        $club = $request->input('club');
        $elo = $request->input('elo_points');
        $team = Team::query()->where('primary_contact_id', $player['id'])->where('number_of_players', 1)->first();
        $team->clubs()->updateExistingPivot($club['id'], ['rating' => $elo]);
        return response()->noContent();
    }
}
