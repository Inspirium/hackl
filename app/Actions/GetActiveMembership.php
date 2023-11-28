<?php

namespace App\Actions;

use App\Models\Membership;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Auth;

class GetActiveMembership
{
    private $active_membership = null;

    public function execute($club = null)
    {
        if (! $this->active_membership) {
            $club = $club ?? request()->get('club')->id;
            //load user membership
            $um = UserMembership::where('user_id', Auth::id())->whereHas('membership', function ($query) use($club) {
                $query->where('club_id', $club);
            })->first();
            if ($um) {
                $this->active_membership = $um->membership;
            }
            if (! $this->active_membership) {
                // get club basic
                $this->active_membership = Membership::where('club_id', $club)->where('basic', 1)->first();
            }
        }

        return $this->active_membership;
    }
}
