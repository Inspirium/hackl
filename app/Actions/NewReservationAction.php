<?php

namespace App\Actions;

use App\Events\NewReservationCreated;
use App\Exceptions\CourtTimeUnavailable;
use App\Models\Court;
use App\Models\Game;
use App\Models\Message;
use App\Models\Reservation;
use App\Models\SchoolGroup;
use App\Models\Team;
use App\Notifications\AddedToMultipleReservations;
use App\Notifications\AddedToReservation;
use App\Notifications\NewMultipleReservation;
use App\Notifications\NewReservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class NewReservationAction
{

    private $series = null;
    private $ts = [];
    private $active_membership;

    private $multiple_reservations = false;

    public function executeFromRequest(Request $request)
    {
        if ($request->get('club')->validate_user) {
            $pivot = DB::table('club_user')
                ->where('club_id', $request->get('club')->id)
                ->where('player_id', \Auth::id())
                ->first();
            if ($pivot->status === 'applicant') {
                return;
            }
        }
        return $this->players($request);
    }

    public function players(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $court = Court::with(['club'])->find($request->input('court_id'));
        $club = $court->club;
        $m = new GetActiveMembership();
        $this->active_membership = $m->execute($club->id);
        $court->load(
            ['reservations' => function ($query) use ($date) {
                $query->whereDate('from', $date);
            }]
        );
        $times = collect($request->input('times'));
        $step = $court->reservation_duration / 60;
        $times = $times->map(function ($item) {
            return $item['time'] / 100;
        })->sort()->all();
        //since we sorted it, we can assume it is the starting time
        if (count($times) === 1) {
            $from = $times[0];
            $to = $from + $step;
        } else {
            $from = array_shift($times);
            $to = array_pop($times) + $step;
        }
        $players = $request->input('players');
        $group = $request->input('group');
        $name = $request->input('name');
        $type = 'players';
        if ($request->input('type')) {
            $type = $request->input('type');
        }
        if ($group && $group['id']) {
            $group = SchoolGroup::with(['players'])->find($group['id']);
            $players = array_merge($group->players->toArray(), $players);
            $name = $group->name;
            $type = 'group-'.$group->id;
        }
        $watchers = $request->has('watchers') ? $request->input('watchers') : [];
        if ($request->has('repeat')) {
            $this->series = Str::uuid();
        }
        $reservations = [];
        $reservations[] = $this->createSingleReservation($court, $date, $from, $to, $players, $watchers, $name, $type, $request->input('message_id'), $request);
        if ($request->has('repeat')) {
            $repeat = intval($request->input('repeat'));
            $everyOtherWeek = $request->input('every_other_week');
            if ($repeat > 10 && ! in_array($club->id, \Auth::user()->is_admin->toArray())) {
                $repeat = 10;
            }
            if ($repeat > 1) {
                $this->multiple_reservations = true;
            }
            for ($i = 0; $i < $repeat; $i++) {
                $date = Carbon::createFromFormat('Y-m-d', $date)->addWeeks($everyOtherWeek?2:1)->format('Y-m-d');
                try {
                    $reservations[] = $this->createSingleReservation($court, $date, $from, $to, $players, $watchers, $name, $type, $request->input('message_id'), $request);
                } catch (CourtTimeUnavailable $e) {
                    continue;
                }
            }
            if ($this->multiple_reservations) {
                $reservation = $reservations[0];
                foreach ($reservation->players as $player) {
                    foreach ($player->players as $p) {
                        $p->notify((new AddedToMultipleReservations($reservation, \Auth::user()))->locale($p->lang));
                    }
                    //Notification::send($player->players, new AddedToMultipleReservations($reservation, \Auth::user()));
                }
                //send to admins
                //Notification::send($club->all_admins, new NewMultipleReservation($reservation, \Auth::user()));
                foreach ($club->all_admins as $admin) {
                    $admin->notify((new NewMultipleReservation($reservation, \Auth::user()))->locale($admin->lang));
                }

            }
        }
        $prev = 0;
        foreach ($this->ts as $time) {
            $this->ts['diffs'][] = $time - $prev;
            $prev = $time;
        }
        return $reservations;
    }

    /**
     * @param  Court  $court
     * @param $date
     * @param $from
     * @param $to
     * @return Reservation|\Illuminate\Database\Eloquent\Model|null
     *
     * @throws CourtTimeUnavailable
     */
    private function createSingleReservation(Court $court, $date, $from, $to, $players, $watchers, $name, $type, $message_id, Request $request)
    {
        $from_time = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $date, $this->timeFromDecimal($from)));
        $to_time = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $date, $this->timeFromDecimal($to)));

        // check if not allowed and exit
        /*if ($request->get('club')->id == 26) {
            if (Carbon::now()->diffInDays($from_time) > 7) {
                return null;
            }
        } else
            */
        if ($this->active_membership && !in_array($court->club->id, \Auth::user()->is_admin->toArray())) {
            if (Carbon::now()->diffInDays($from_time) > $this->active_membership->max_reservation_span) {
               // return null;
            }
        }
        $group_id = null;
        if (strpos($type, 'group') > -1) {
            $group_id = explode('-', $type)[1];
            $type = 'group';
        }
        $reservation = Reservation::create([
            'name' => $name,
            'type' => $type,
            'school_group_id' => $group_id,
            'from' => $from_time->timestamp,
            'to' => $to_time->timestamp,
            'court_id' => $court->id,
            'series' => $this->series,
            'needs_payment' => $court->needs_payment,
            'status' => 'pending',
            'applicant' => $request->input('applicant'),
            'description' => $request->input('description'),
            'email' => $request->input('email'),
            'note' => $request->input('note'),
            'public_description' => $request->input('public_description'),
        ]);
        if ($request->input('category')) {
            $reservation->category()->associate($request->input('category.id'));
        }
        if (! $request->input('game')) {
            $game = Game::create([
                'type_type' => Reservation::class,
                'type_id' => $reservation->id,
                'court_id' => $court->id,
            ]);
            $reservation->game()->associate($game);
            $reservation->save();
            foreach ($players as $player) {
                if (isset($player['id'])) {
                    if (isset($player['type'])  && $player['type'] === 'player') {
                        $team = Team::query()->where('number_of_players', 1)->where('primary_contact_id', $player['id'])->first();
                    } else {
                        $team = Team::find($player['id']);
                    }
                    if (!$team) {
                        continue;
                    }
                    $reservation->players()->attach($team->id, ['status' => 'player']);
                    $game->players()->attach($team->id);
                }
            }
        } else {
            $game = Game::find($request->input('game'));
            $reservation->game()->associate($game);
            $players = $game->players;
            $reservation->save();

            foreach ($players as $player) {
                $reservation->players()->attach($player->id, ['status' => 'player']);
            }
        }
        //send to partner
        if (!$this->multiple_reservations) {
            foreach ($reservation->players as $player) {
                foreach ($player->players as $p) {
                    $p->notify((new AddedToReservation($reservation, \Auth::user()))->locale($p->lang));
                }
                //Notification::send($player->players, new AddedToReservation($reservation, \Auth::user()));
            }

            //send to admins
            $admins = $court->club->all_admins;
           // Notification::send($admins, new NewReservation($reservation, \Auth::user()));
            foreach ($admins as $admin) {
                $admin->notify((new NewReservation($reservation, \Auth::user()))->locale($admin->lang));
            }
        }
        broadcast(new NewReservationCreated($reservation));

        //send to watchers
        /*foreach ($watchers as $player) {
            if (isset($player['id']) && $player['id']) {
                $reservation->watchers()->attach($player['id'], ['status' => 'watcher']);
            }
        }*/

        //delete message if from messages
        if ($message_id) {
            Message::find($message_id)->delete();
        }

        return $reservation;
    }

    private function timeFromDecimal($number)
    {
        $hour = intval($number);
        $minutes = ($number - $hour) * 60;
        return sprintf('%02d:%02d', $hour, $minutes);
    }
}
