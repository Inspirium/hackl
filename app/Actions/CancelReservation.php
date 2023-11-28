<?php

namespace App\Actions;

use App\Events\NewReservationCreated;
use App\Exceptions\ReservationNotCanceable;
use App\Models\Club;
use App\Models\Membership;
use App\Models\Reservation;
use App\Models\Team;
use App\Models\UserMembership;
use App\Notifications\ReservationCanceled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CancelReservation
{
    private $active_membership;

    private $club;

    public function execute(Reservation $reservation, Club $club)
    {

        $this->club = $reservation->court->club;
        $user = Auth::user();
        if (!($user->is_admin->contains($club->id) || (isset($user->club_member[$club->id]) && $user->club_member[$club->id]['cashier']))) {
            if (Carbon::now()->diffInMinutes($reservation->from, false) < 0) {
                throw new ReservationNotCanceable('Reservation cannot be canceled because it is in the past');
            }
            $this->load_membership();
            // we check if we have an active membership and that the time is smaller then allowed
            // throw error and stop, othwerwise continue
            if ($this->active_membership) {
                if (
                    !$this->active_membership->is_reservation_cancelable ||
                    Carbon::now()->diffInHours($reservation->from) < $this->active_membership->reservation_cancelable
                ) {
                    throw new ReservationNotCanceable('Reservation cannot be canceled because it is too late');
                }
            }
        }

        $notifiables = [];
        /** @var Team $team */
        foreach ($reservation->players as $team) {
            foreach ($team->players as $player) {
                $notifiables[] = $player;
            }
        }
        foreach ($reservation->watchers as $watcher) {
            $notifiables[] = $watcher;
        }
        foreach (request()->get('club')->all_admins as $admin) {
            $notifiables[] = $admin;
        }
        $notifiables = array_unique($notifiables, SORT_REGULAR);
        foreach ($notifiables as $notifiable) {
            $notifiable->notify((new ReservationCanceled($reservation, Auth::user()))->locale($notifiable->lang));
        }
        //\Notification::send($notifiables, new ReservationCanceled($reservation, Auth::user()));
        broadcast(new NewReservationCreated($reservation));
        $reservation->delete();

        return true;
    }

    private function load_membership()
    {
        if (! $this->active_membership) {
            //load user membership
            $um = UserMembership::where('user_id', Auth::id())->whereHas('membership', function ($query) {
                $query->where('club_id', $this->club->id);
            })->first();
            if ($um) {
                $this->active_membership = $um->membership;
            }
            if (! $this->active_membership) {
                // get club basic
                $this->active_membership = Membership::where('club_id', $this->club->id)->where('basic', 1)->first();
            }
        }
    }
}
