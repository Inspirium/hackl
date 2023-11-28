<?php

namespace App\Http\Controllers\V2;

use App\Actions\CancelReservation;
use App\Actions\NewReservationAction;
use App\Events\NewReservationCreated;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ReservationResource;
use App\Models\Membership;
use App\Models\Reservation;
use App\Models\SchoolGroup;
use App\Models\Team;
use App\Models\UserMembership;
use App\Notifications\AddedToReservation;
use App\Notifications\InvitedToReservation;
use App\Notifications\ReservationCanceled;
use App\Notifications\WatcherAnnounced;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReservationController extends Controller
{
    /**
     * @var Membership|null
     */
    private $active_membership = null;

    public function __construct()
    {
        $this->middleware('auth:api')->except('index');
    }

    // TODO: move to middleware
    private function load_membership()
    {
        if (! $this->active_membership) {
            //load user membership
            $um = UserMembership::where('user_id', Auth::id())->whereHas('membership', function ($query) {
                $query->where('club_id', request()->get('club')->id);
            })->first();
            if ($um) {
                $this->active_membership = $um->membership;
            }
            if (! $this->active_membership) {
                // get club basic
                $this->active_membership = Membership::where('club_id', request()->get('club')->id)->where('basic', 1)->first();
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return ReservationCollection
     */
    public function index(Request $request)
    {
        $club = $request->get('club');
        if (!$club || $club->id === 26) {
            $club = 0;
        } else {
            $club = $club->id;
        }
        $reservations = QueryBuilder::for(Reservation::class)
            ->allowedFilters([
                'name',
                AllowedFilter::callback('player', function ($query, $value) {
                    $query->whereHas('game', function ($query) use ($value) {
                        $query->whereHas('players', function ($q) use ($value) {
                            $q->whereHas('players', function ($q) use ($value) {
                                $q->where('player_id', $value);
                            });
                        });
                    });
                }),
                AllowedFilter::callback('team', function ($query, $value) {
                    $query->whereHas('game', function ($query) use ($value) {
                        $query->whereHas('players', function ($q) use ($value) {
                            $q->where('user_id', $value);
                        });
                    });
                }),
                AllowedFilter::callback('club', function ($query, $value) {
                    if ($value) {
                        $query->whereHas('court', function ($q) use ($value) {
                            $q->where('club_id', $value);
                        });
                    }
                })->default($club),
                'school_group_id',
                AllowedFilter::callback('from', function ($query, $value) {
                    $query->whereDate('from', '>=', $value);
                }),
                AllowedFilter::callback('to', function ($query, $value) {
                    $query->whereDate('to', '<=', $value);
                }),
                AllowedFilter::exact('court', 'court_id'),
                AllowedFilter::callback('canceled', function ($query, $value) {
                    $query->onlyTrashed();
                }),
                AllowedFilter::callback('active_between', function($query, $value) {
                        $query->whereBetween('from',  [ Carbon::parse($value[0]), Carbon::parse($value[1]??'now') ]);
                }),
                AllowedFilter::callback('category', function($query, $value) {
                    if ($value === 'all') {
                        return $query->whereHas('category');
                    }
                    if ($value === 'none') {
                        return $query->whereDoesntHave('category');
                    }
                    $id = intval($value);
                    if ($id) {
                        return $query->where('category_id', $id);
                    }
                    return $query->whereHas('category', function($q) use ($value) {
                        $q->where('name', 'LIKE', '%' . $value . '%');
                    });
                }),
                AllowedFilter::callback('series', function($query, $value) {
                    if ($value == 1) {
                        return $query->whereNotNull('series');
                    }
                    if ($value == 0) {
                        return $query->whereNull('series');
                    }
                    return $query->where('series', $value);
                }),
                'status'
            ])
            ->allowedIncludes(['attendances', 'school_group', 'payment_user', 'players.wallet', 'payments', 'school_performances'])
            ->orderBy('id', 'desc')
            ->with(['court', 'players', 'watchers'])
            ->paginate($request->input('limit'))
            ->appends($request->query());
        return new ReservationCollection($reservations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  NewReservationAction  $newReservationAction
     * @return ReservationCollection
     */
    public function store(Request $request, NewReservationAction $newReservationAction)
    {
        $reservations = collect($newReservationAction->executeFromRequest($request));

        return response()->json(['message' => 'success', 'reservations' => $reservations->pluck('id')]);
        //return ReservationCollection::make($reservations);
    }

    /**
     * Display the specified resource.
     *
     * @param  Reservation  $reservation
     * @return ReservationResource
     */
    public function show($reservation)
    {
        $reservation = QueryBuilder::for(Reservation::query()->where('id', $reservation))
            ->allowedIncludes(['attendances', 'school_group', 'payment_user', 'players.wallet', 'payments', 'watchers', 'court', 'school_performances'])
            ->with([ 'players.players.wallets', 'game.players.players.wallets','players.players.memberships', 'game.players.players.memberships'])
            ->first();

        return new ReservationResource($reservation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        $group = $request->input('group');
        if ($request->has('name')) {
            $reservation->name = $request->input('name');
        }
        if ($group && $group['id']) {
            $group = SchoolGroup::with(['players'])->find($group['id']);
            foreach ($group->players as $player) {
                $reservation->players()->attach($player->id, ['status' => 'player']);
            }
            $reservation->name = $group->name;
            $reservation->type = 'group-'.$group->id;
        }
        if ($request->has('players') && count($request->input('players'))) {
            $reservation_players = [];
            $game_players = [];
            foreach ($request->input('players') as $item) {
                if (!isset($item['id'])) {
                    continue;
                }
                if (!isset($item['type']) || $item['type'] === 'player') {
                    $team = Team::where('number_of_players', 1)->where('primary_contact_id', $item['id'])->first();
                } else {
                    $team = Team::find($item['id']);
                }
                $reservation_players[$team->id] = ['status' => 'player'];
                $game_players[$team->id] = ['player' => 1];
            }
            $reservation->players()->sync($reservation_players);
            if ($reservation->game) {
                $reservation->game->players()->sync($game_players);
            }
            foreach ($reservation->players as $player) {
                foreach ($player->players as $p) {
                    $p->notify((new AddedToReservation($reservation, \Auth::user()))->locale($p->lang));
                }
                   // \Notification::send($player->players, new AddedToReservation($reservation, \Auth::user()));
            }
        }
        if ($request->has('watchers') && count($request->input('watchers'))) {
            $players = collect($request->input('watchers'))->mapWithKeys(function ($item) {
            return [$item['id'] => ['status' => 'watcher']];
            });
            $reservation->watchers()->sync($players);
            foreach ($reservation->watchers() as $player) {
                $player->notify((new InvitedToReservation($reservation, \Auth::user()))->locale($player->lang));
            }
        }
        if ($request->has('watcher')) {
            $reservation->watchers()->toggle($request->input('watcher.id'));
            $reservation->load(['watchers', 'court', 'players']);
            foreach ($reservation->players as $player) {
                foreach ($player->players as $p) {
                    $p->notify((new WatcherAnnounced($reservation, \Auth::user()))->locale($p->lang));
                }
                //\Notification::send($player->players, new WatcherAnnounced($reservation, \Auth::user()));
            }
        }
        if ($request->has('is_paid')) {
            if ($request->input('is_paid')) {
                if ($request->input('payment_invoice') && $reservation->payment_invoice !== $request->input('payment_invoice')) {
                    $reservation->payment_invoice = $request->input('payment_invoice');
                }
                $reservation->payment_note = $request->input('payment_note');
                $reservation->payment_user_id = Auth::id();
                $reservation->payment_date = Carbon::now();
            } else {
                $reservation->payment_invoice = null;
                $reservation->payment_date = null;
            }
        }
        $reservation->save();
        broadcast(new NewReservationCreated($reservation));

        return ReservationResource::make($reservation);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation, CancelReservation $cancelReservation)
    {

        $cancelReservation->execute($reservation, request()->get('club'));

        return response()->noContent();
    }

    public function deleteMany(Request $request, CancelReservation $cancelReservation)
    {
        $reservations = $request->input('reservations');
        foreach ($reservations as $reservation) {
            if ($reservation['id']) {
                $r = Reservation::find($reservation['id']);
                if ($r) {
                    $cancelReservation->execute(Reservation::find($reservation['id']), $request->get('club'));
                }
            }
        }

        return response()->noContent();
    }
}
