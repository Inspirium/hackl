<?php

namespace App\Http\Controllers\V2;

use App\Models\Club;
use App\Models\User;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserMembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $player)
    {
        $memberships = QueryBuilder::for(UserMembership::where('user_id', $player->id))
            ->allowedFilters([
                AllowedFilter::callback('club', function($query, $value) {
                    $query->whereHas('membership.club', function($query) use ($value) {
                        $query->where('id', $value);
                    });
                }),
            ])
            ->get();

        return $memberships;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $player)
    {
        /** @var Club $club */
        $club = $request->get('club');
        $cm = $club->memberships()->find($request->input('membership.id'));

        $membership = UserMembership::query()
            ->where('user_id', $player->id)
            ->where('membership_id', $cm->id)
            ->first();
        if (! $membership) {
            // TODO: delete other memberships, user can have only one in a club
            $membership = UserMembership::create([
                'user_id' => $player->id,
                'membership_id' => $cm->id,
                'price' => $cm->price,
                'expires_at' => Carbon::now()->addYear(), //TODO: to actual membership value
            ]);
        }

        return $membership;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserMembership  $userMembership
     * @return \Illuminate\Http\Response
     */
    public function show(UserMembership $membership)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserMembership  $userMembership
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserMembership $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserMembership  $userMembership
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $player, UserMembership $membership)
    {
        $membership->delete();

        return response()->noContent();
    }
}
