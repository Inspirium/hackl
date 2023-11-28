<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Models\Club;
use App\Models\Court;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\AddedToReservation;
use App\Notifications\NewReservation;
use App\Notifications\ReservationCanceled;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ClubController extends Controller
{
    public function getClub(Request $request)
    {
        $origin = parse_url($request->header('origin'))['host'];
        //$origin = 'zapresic.hts.test';
        if (strpos($origin, 'hts.test') !== false || strpos($origin, 'localhost') !== false || strpos($origin, 'inspirium.hr') !== false) {
            $subdomain = 'zapresic';
            $club = Club::where('subdomain', $subdomain)->first();
        } elseif (strpos($origin, env('APP_DOMAIN')) !== false) {
            $subdomain = str_replace('.'.env('APP_DOMAIN'), '', $origin);
            $club = Club::where('subdomain', $subdomain)->first();
        } else {
            $club = Club::where('domain', $origin)->first();
        }

        return response()->json($club);
    }

    public function getCourts(Request $request, $day = null)
    {
        $courts = $request->get('club')->courts()->where('is_active', true)->get();
        $out = [];
        if (! $day) {
            $day = date('Y-m-d');
        }
        /** @var Court $court */
        foreach ($courts as $court) {
            $court->load(['reservations' => function ($query) use ($day) {
                $query->whereDate('from', $day);
            },
                'working_hours' => function ($query) use ($day) {
                    $query->whereDate('active_from', '>', $day);
                    $query->whereDate('active_to', '<', $day);
                }, ]);
            $court->parsed_reservations = $court->getParsedReservations($day);
            $out[] = $court;
        }

        return response()->json($out);
    }

    /**
     * @param $number
     * @return string
     *
     * TODO: move to a helper file
     */
    private function timeFromDecimal($number)
    {
        $hour = intval($number);
        $minutes = ($number - $hour) * 60;

        return sprintf('%02d:%02d', $hour, $minutes);
    }

    public function postReservation(Request $request)
    {
        $date = $request->input('date');
        $court = Court::find($request->input('court_id'));
        $court->load(
            ['reservations' => function ($query) use ($date) {
                $query->whereDate('from', $date);
            }]
        );
        $times = collect($request->input('times'));
        $step = $court->reservation_duration / 60;
        $times = $times->sort()->map(function ($item) {
            return $item / 100;
        })->all();
        $reservation = false;
        if (count($times) === 1) {
            $from = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $date, $this->timeFromDecimal($times[0])));
            $to = $from->copy()->addMinutes($court->reservation_duration);
            if ($this->checkFreeReservation($from, $to, $court->id)) {
                $reservation = new Reservation([
                    'from' => $from->timestamp,
                    'to' => $to->timestamp,
                ]);
                $reservation->save();
                $reservation->court()->associate($court);
                $reservation->players()->attach(\Auth::user(), ['status' => 'player']);
                $reservation->save();
            }
        } else {
            //since we sorted it, we can assume it is the starting time
            $from = [$times[0]];
            $to = [];
            $continous = true;
            //now we check if it is continous, or do we have two reservations
            for ($i = $from[0]; $i <= ($times[count($times) - 1] + $step); $i += $step) {
                if (! in_array($i, $times)) {
                    if ($continous) {
                        $to[] = $i;
                    }
                    $continous = false;
                } else {
                    if (! $continous) {
                        //we have a second starting
                        $from[] = $i;
                        $continous = true;
                    }
                }
            }
            for ($i = 0; $i < count($from); $i++) { //TODO: only one at the time

                $from_time = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $date, $this->timeFromDecimal($from[$i])));
                $to_time = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $date, $this->timeFromDecimal($to[$i])));
                if ($this->checkFreeReservation($from_time, $to_time, $court->id)) {
                    $reservation = new Reservation([
                        'from' => $from_time->timestamp,
                        'to' => $to_time->timestamp,
                    ]);
                    $reservation->save();
                    $reservation->court()->associate($court);
                    $reservation->players()->attach(\Auth::user(), ['status' => 'player']);
                    $reservation->save();
                }
            }
        }

        //send notification to admins
        if ($reservation) {
            foreach ($request->get('club')->all_admins as $admin) {
                $admin->notify((new NewReservation($reservation, \Auth::user()))->locale($admin->lang));
            }
            //Notification::send($request->get('club')->all_admins, new NewReservation($reservation));

            return response()->json(['reservation' => $reservation->id]);
        }

        return response()->json(['errors' => ['Već postoji rezervacija za traženo vrijeme']], 409);
    }

    public function deleteReservation(Reservation $reservation)
    {
        $users = $reservation->players;
        foreach ($users as $user) {
            $user->notify((new ReservationCanceled(Auth::user(), $reservation))->locale($user->lang));
        }
        //Notification::send($users, new ReservationCanceled(Auth::user(), $reservation));
        $reservation->delete();

        return response()->json();
    }

    public function addPlayersToReservation(Request $request, Reservation $reservation)
    {
        $reservation->players()->attach($request->input('player'), ['status' => 'player']);
        $player = User::find($request->input('player'));
        $player->notify((new AddedToReservation($reservation, Auth::user()))->locale($player->lang));

        return response()->json();
    }

    public function getDoubleReservations(Request $request, $day = null)
    {
        if ($day) {
            $day2 = Carbon::createFromFormat('Y-m-d', $day)->addDay(1)->format('Y-m-d');
        } else {
            $day = date('Y-m-d');
            $day2 = Carbon::now()->addDay(1)->format('Y-m-d');
        }
        if (!$request->get('club')) {
            return response()->noContent();
        }
        if ($request->get('club')->id == 26) {
            $courts = Court::all()->map(function ($item) {
                return $item->id;
            });
        } else {
            $courts = $request->get('club')->courts->map(function ($item) {
                return $item->id;
            });
        }
        //$reservations = Reservation::where('to', '>', $day)
        //        ->with(['game'])
        //        ->where('to', '<=', $day2)->whereIn('court_id', $courts)->orderBy('from', 'asc')->with(['court', 'players', 'watchers'])->get();
        $reservations = \Spatie\QueryBuilder\QueryBuilder::for(Reservation::class)
            ->allowedIncludes(['court', 'players', 'watchers', 'game'])
            ->where('status', 'pending')
            ->whereIn('court_id', $courts)
            ->where('to', '>', $day)
            ->where('to', '<=', $day2)
            ->orderBy('from', 'asc')
            ->paginate($request->input('limit'))
            ->appends(request()->query());
        return  ReservationResource::collection($reservations);
    }

    public function updateWeather(Request $request)
    {
        $type = $request->input('type');
        $request->get('club')->weather = ! $request->get('club')->weather;
        $request->get('club')->save();

        return response()->json(['message' => 'success']);
    }

    public function saveImage(Request $request)
    {
        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if (! $file->isValid()) {
                return response()->json(['result' => 'error', 'message' => 'Invalid file upload'], 400);
            }
            $path = $file->store(sprintf('%s/%d/%d', 'logos', date('Y'), date('m')), 'public');
            $request->get('club')->logo = $path;
            $request->get('club')->save();

            return response()->json($request->get('club')->logo);
        }

        return response()->json();
    }

    public function repeatReservation(Request $request, Reservation $reservation)
    {
        $times = intval($request->input('times.0'));
        if (! $request->input('repeat')) {
            $errors = [];
            for ($i = 1; $i <= $times; $i++) { //check all times
                if (! $this->checkFreeReservation($reservation->from->addDays(7 * $i), $reservation->to->addDays(7 * $i), $reservation->court_id)) {
                    $errors[] = 'Rezervacija za dan: '.$reservation->from->addDays(7 * $i)->toDateString().' nije moguća.';
                }
            }
            if (count($errors)) {
                return response()->json(['errors' => $errors], 409);
            }
        }
        for ($i = 1; $i <= $times; $i++) {
            if ($this->checkFreeReservation($reservation->from->addDays(7 * $i), $reservation->to->addDays(7 * $i), $reservation->court_id)) {
                $new_reservation = new Reservation();
                $new_reservation->court_id = $reservation->court_id;
                $new_reservation->from = $reservation->from->addDays(7 * $i);
                $new_reservation->to = $reservation->to->addDays(7 * $i);
                $new_reservation->save();
                $new_reservation->players()->attach($reservation->players[0]);
            }
        }

        return response()->json([]);
    }

    public function removePlayersFromReservation(Request $request, Reservation $reservation, User $player)
    {
        $reservation->players()->detach($player);
        //TODO: notification?
    }

    /**
     * https://stackoverflow.com/a/11098996
     *
     * @param  Carbon  $from
     * @param  Carbon  $to
     * @return bool
     */
    public function checkFreeReservation($from, $to, $court_id)
    {
        $reservations = Reservation::where('court_id', $court_id)->whereDate('from', '=', $from->toDateString())->get();
        foreach ($reservations as $reservation) {
            if (($reservation->from >= $from && $reservation->from < $to) || ($from >= $reservation->from && $from < $reservation->to)) {
                return false;
            }
        }

        return true;
    }
}
