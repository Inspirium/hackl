<?php

namespace App\Models;

use App\Actions\UpdateCourtWeather;
use App\Notifications\WeatherUpdate;
use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class Court
 *
 * @property $id
 * @property $name
 * @property $type
 * @property $is_active
 * @property $surface_id
 * @property $working_from
 * @property $working_to
 * @property $lights
 * @property $reservation_confirmation
 * @property $reservation_duration
 * @property int|null $club_id
 * @property int|null $cover
 * @property int|null $weather
 * @property-read \App\Models\Club|null $club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reservation[] $reservations
 * @property-read \App\Models\Surface $surface
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\WorkingHours[] $working_hours
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereLights($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereReservationConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereReservationDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereSurfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereWeather($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereWorkingFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Court whereWorkingTo($value)
 * @mixin \Eloquent
 * @property int $reservation_hole
 * @property int|null $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @property-read int|null $players_count
 * @property-read int|null $reservations_count
 * @property-read int|null $working_hours_count
 * @method static \Illuminate\Database\Eloquent\Builder|Court newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Court newQuery()
 * @method static \Illuminate\Database\Query\Builder|Court onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Court query()
 * @method static \Illuminate\Database\Eloquent\Builder|Court whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Court whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Court whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Court whereReservationHole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Court whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Court withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Court withoutTrashed()
 * @property string|null $hero_image
 * @method static \Illuminate\Database\Eloquent\Builder|Court whereHeroImage($value)
 */
class Court extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'courts';

    protected $guarded = [];

    protected $casts = [
        'name' => 'string',
        'is_active' => 'boolean',
        'type' => 'string',
        'working_from' => 'string',
        'working_to' => 'string',
        'lights' => 'boolean',
        'reservation_confirmation' => 'boolean',
        'reservation_duration' => 'integer',
        'show_on_tenisplus' => 'boolean',
        'member_reservation' => 'boolean',
        'weather' => 'boolean',
        'court_break_from' => 'datetime',
        'court_break_to' => 'datetime',
        'wifi' => 'boolean',
        'invalid' => 'boolean',
        'airconditioner' => 'boolean',
        'size' => 'integer',
        'heating'   => 'boolean',
    ];

    protected $with = ['surface'];

    public function club()
    {
        return $this->belongsTo('App\Models\Club', 'club_id');
    }

    public function reservations()
    {
        return $this->hasMany('App\Models\Reservation');
    }

    public function working_hours()
    {
        return $this->hasMany('App\Models\WorkingHours');
    }

    public function sports() {
        return $this->belongsToMany(Sport::class);
    }

    public function surface()
    {
        return $this->belongsTo('App\Models\Surface', 'surface_id');
    }

    public function getWorkingHours($date, $user = null, $parsed = false)
    {
        if (!$user) {
            $user = Auth::user();
        }
        $date = Carbon::createFromFormat('Y-m-d', $date);
        $hours = [];
        if (is_a($user, User::class)) {
            $hours = $this->working_hours()->where('membership_id', $user->getMembership($this->club)->id)->get();
        }
        else {
            if ($user && $user->primary_contact) {
                $hours = $this->working_hours()->where('membership_id', $user->primary_contact->getMembership($this->club)->id)->get();
            }
        }
        $hours_default = $this->working_hours()->whereNull('membership_id')->get();
        $out = [];
        $step = $this->attributes['reservation_duration'] / 60;
        foreach ($hours_default as $one) {
            $day = explode(' ', $one->cron);
            //dd($day);
            $start_at_half = false;
            if ($day[0] === '30') {
                $start_at_half = true;
            }
            if ($day[1] === '*') {
                $hour = [0, 24];
            } else {
                $hour = explode('-', $day[1]);
            }
            $day[0] = '*';
            $day[1] = '*';
            $day = implode(' ', $day);
            if ((new CronExpression($day))->isDue($date)) {
                $from = intval($hour[0]);
                if ($start_at_half) {
                    $from += 0.5;
                }
                $to = intval($hour[1]);
                if (! $to) {
                    $to = 24;
                }
                for ($i = $from; $i < $to; $i += $step) {
                    $out[(string)$i] = ['hour' => $i, 'price' => $one->price];
                }
            }
        }
        if ($hours) {
            foreach ($hours as $one) {
                $day = explode(' ', $one->cron);
                $start_at_half = false;
                if ($day[0] === '30') {
                    $start_at_half = true;
                }
                if ($day[1] === '*') {
                    $hour = [0, 24];
                } else {
                    $hour = explode('-', $day[1]);
                }
                $day[0] = '*';
                $day[1] = '*';
                $day = implode(' ', $day);
                if ((new CronExpression($day))->isDue($date)) {
                    $from = intval($hour[0]);
                    if ($start_at_half) {
                        $from += 0.5;
                    }
                    $to = intval($hour[1]);
                    if (! $to) {
                        $to = 24;
                    }
                    for ($i = $from; $i < $to; $i += $step) {
                        $out[(string)$i] = ['hour' => $i, 'price' => $one->price];
                    }
                }
            }
        }
        $out = array_values($out);
        usort($out, function ($a, $b) {
            return $a['hour'] <=> $b['hour'];
        });
        return $out;
    }

    public function getParsedReservations($date)
    {
        $out = [];
        $step = $this->attributes['reservation_duration'] / 60;
        $workingHours = $this->getWorkingHours($date);
        foreach ($this->reservations as $reservation) {
            $from = $reservation->from->hour + $reservation->from->minute / 60;
            $to = $reservation->to->hour + $reservation->to->minute / 60;
            if (! $to) {
                $to = 24;
            }
            for ($i = $from; $i < $to; $i += $step) {
                $key = $i * 100;
                if ($reservation->type === 'group') {
                    $out[$key] = [
                        'time' => $key,
                        'name' => $reservation->name,
                        'start_time' => $from * 100,
                        'end_time' => $to * 100,
                        'from' => $reservation->from,
                        'type' => 'school_group',
                        'players' => [
                            [
                                'id' => $reservation->schoolGroup?$reservation->schoolGroup->id:'#',
                                'image' => $reservation->schoolGroup?$reservation->schoolGroup->image:'#',
                                'display_name' => $reservation->schoolGroup?$reservation->schoolGroup->name:'Deleted',
                            ],
                        ],
                        'id' => $reservation->id,
                        'watchers' => $reservation->watchers,
                        'is_paid' => $reservation->is_paid ?? $reservation->category->is_paid,
                        'payment_invoice' => $reservation->payment_invoice,
                        'category' => $reservation->category,
                        'created_by' => $reservation->created_by,
                        'applicant' => $reservation->applicant,
                        'email' => $reservation->email,
                        'description' => $reservation->description,
                        'note' => $reservation->note,
                        'public_description' => $reservation->public_description,
                        'status' => $reservation->status,
                        'price' => $reservation->status !== 'pending' ? '' : $this->getPrice($key, $workingHours)
                    ];
                } else {
                    if ($reservation->game && $reservation->game->players) {
                        $players = $reservation->game->players;
                    } else {
                        $players = $reservation->players()->get();
                    }
                    $o_players = [];
                    foreach ($players as $team) {
                        foreach ($team->players as $player) {
                            $um = UserMembership::where('user_id', $player->id)->whereHas('membership', function ($query) {
                                $query->where('club_id', $this->club_id);
                            })->first();
                            $o_players[] =
                                [
                                    'id' => $player->id,
                                    'image' => $player->image,
                                    'display_name' => $player->display_name,
                                    'membership' => $um ? $um->membership->name : '',
                                    'team_id' => $team->id,
                                ];
                        }
                    }
                    $type = $reservation->type;
                    if ($type === 'player') {
                        $type = 'individual';
                    }
                    $out[$key] = ['time' => $key,
                        'name' => $reservation->name,
                        'is_paid' => $reservation->is_paid ?? $reservation->category->is_paid,
                        'payment_invoice' => $reservation->payment_invoice,
                        'from' => $reservation->from,
                        'start_time' => $from * 100,
                        'end_time' => $to * 100,
                        'players' => $o_players,
                        'id' => $reservation->id,
                        'watchers' => $reservation->watchers,
                        'competition' => $reservation->game ? [
                            'type' => $reservation->game->type_type,
                            'object' => $reservation->game->type,
                        ] : null,
                        'category' => $reservation->category,
                        'type' => $type,
                        'created_by' => $reservation->created_by,
                        'applicant' => $reservation->applicant,
                        'email' => $reservation->email,
                        'description' => $reservation->description,
                        'note' => $reservation->note,
                        'public_description' => $reservation->public_description,
                        'status' => $reservation->status,
                        'price' => $reservation->status !== 'pending' ? '' : $this->getPrice($key, $workingHours),
                    ];
                }
            }
        }


        if ($workingHours) {
            $end = ($workingHours[count($workingHours) - 1]['hour'] + $step) * 100;
            $hour = $workingHours[0]['hour'] * 100;

            $step = $step * 100;
            while ($hour < $end) {
                $key = intval($hour);
                if (!array_key_exists($key, $out)) {
                    $out[$key] = ['time' => $key, 'players' => [], 'id' => 0, 'price' => $this->getPrice($hour, $workingHours)];
                }
                $hour += $step;
            }
        }
        ksort($out);

        return $out;
    }

    public function getParsedReservations2($date)
    {
        $out = [];
        $step = $this->attributes['reservation_duration'] / 60;
        if ($this->attributes['reservation_duration'] == 90) {
            $step = 0.5;
        }
        foreach ($this->reservations as $reservation) {
            $from = $reservation->from->hour + $reservation->from->minute / 60;
            $to = $reservation->to->hour + $reservation->to->minute / 60;
            if (! $to) {
                $to = 24;
            }
            for ($i = $from; $i < $to; $i += $step) {
                $key = $i * 100;
                if ($reservation->type === 'group') {
                    $out[$key] = [
                        'time' => $key,
                        'name' => $reservation->name,
                        'start_time' => $from * 100,
                        'end_time' => $to * 100,
                        'from' => $reservation->from,
                        'type' => 'school_group',
                        'players' => [
                            [
                                'id' => $reservation->schoolGroup?$reservation->schoolGroup->id:'#',
                                'image' => $reservation->schoolGroup?$reservation->schoolGroup->image:'#',
                                'display_name' => $reservation->schoolGroup?$reservation->schoolGroup->name:'Deleted',
                            ],
                        ],
                        'id' => $reservation->id,
                        'watchers' => $reservation->watchers,
                        'is_paid' => $reservation->is_paid ?? $reservation->category->is_paid,
                        'payment_invoice' => $reservation->payment_invoice,
                        'category' => $reservation->category,
                        'created_by' => $reservation->created_by
                    ];
                } else {
                    if ($reservation->game && $reservation->game->players) {
                        $players = $reservation->game->players;
                    } else {
                        $players = $reservation->players()->get();
                    }
                    $o_players = [];
                    foreach ($players as $team) {
                        foreach ($team->players as $player) {
                            $um = UserMembership::where('user_id', $player->id)->whereHas('membership', function ($query) {
                                $query->where('club_id', $this->club_id);
                            })->first();
                            $o_players[] =
                                [
                                    'id' => $player->id,
                                    'image' => $player->image,
                                    'display_name' => $player->display_name,
                                    'membership' => $um ? $um->membership->name : '',
                                    'team_id' => $team->id,
                                ];
                        }
                    }
                    $type = $reservation->type;
                    if ($type === 'player') {
                        $type = 'individual';
                    }
                    $out[$key] = ['time' => $key,
                        'name' => $reservation->name,
                        'is_paid' => $reservation->is_paid ?? $reservation->category->is_paid,
                        'payment_invoice' => $reservation->payment_invoice,
                        'from' => $reservation->from,
                        'start_time' => $from * 100,
                        'end_time' => $to * 100,
                        'players' => $o_players,
                        'id' => $reservation->id,
                        'watchers' => $reservation->watchers,
                        'competition' => $reservation->game ? [
                            'type' => $reservation->game->type_type,
                            'object' => $reservation->game->type,
                        ] : null,
                        'category' => $reservation->category,
                        'type' => $type,
                        'created_by' => $reservation->created_by
                    ];
                }
            }
        }
/*
        $workingHours = $this->getWorkingHours($date);
        $end = $workingHours[count($workingHours) - 1]['hour'] * 100;
        $hour = $workingHours[0]['hour'] * 100;

        $step = $step * 100;
        while ($hour < $end) {
            $key = intval($hour);
            if (! array_key_exists($key, $out)) {
                $out[$key] = ['time' => $key, 'players' => [], 'id' => 0, 'price' => $this->getPrice($hour, $workingHours)];
            }
            $hour += $step;
        }
      */
        foreach ($this->getWorkingHours($date, null, 2) as $one) {
            $hour = $one['hour'];
                for ($i = 0; $i < (1 / $step); $i++) {
                    $key = intval(($hour + $step * $i) * 100);
                    if (!array_key_exists($key, $out)) {
                        $out[$key] = ['time' => $key, 'players' => [], 'id' => 0, 'price' => $one['price']];
                    }
                }
        }

        ksort($out);

        return $out;
    }

    private function getPrice($hour, $workingHours) {
        $hour = $hour/100;
        foreach ($workingHours as $one) {
            if ($one['hour'] == $hour) {
                return $one['price'];
            }
        }
    }

    public function players()
    {
        return $this->hasManyThrough(User::class, Reservation::class, 'user_id', 'reservation_id');
    }

    public function setWeather($type, $note = null)
    {
        $update = false;
        switch ($type) {
            case 'all':
            case 1:
                $this->weather = 1;
                $update = true;
                break;
            case 'closed':
                if ($this->type === 'closed') {
                    $this->weather = 1;
                    $update = true;
                }
                break;
            case 'open':
                if ($this->type === 'open') {
                    $this->weather = 1;
                    $update = true;
                }
                break;
            case 'off':
                if ($this->weather == 1) {
                    $this->weather = 0;
                    $update = true;
                }
                break;
            default:
                $this->weather = 0;
                break;
        }
        $this->save();
        $c = new UpdateCourtWeather();
        if ($this->weather) {
            $c->start($this, Carbon::now(), $note);
        }
        else {
            $c->finish($this, Carbon::now());
        }
        if ($update) {
            //notify
            $res = $this->reservations()->with('players')->whereDate('from', date('Y-m-d'))->get();
            $players = [];
            foreach ($res as $reservation) {
                if (Carbon::now()->lt($reservation->from)) {
                    foreach ($reservation->players as $team) {
                        foreach ($team->players as $player) {
                            $players[] = $player;
                        }
                    }
                }
            }
            $players = array_unique($players);
            return $players;
        }
        return [];
    }

    public function getHeroImageAttribute($value)
    {
        if (! $value) {
            $value = 'https://zapresic.tenis.plus/hero_courts.jpg';
        }

        return $value;
    }

    public function needsPayment(): Attribute {
        return Attribute::make(
           get: function() {
               // get club
               //get user
               $user = \Auth::user();

               //get user membership
               if ($user) {
                   if ($user->is_admin->contains($this->attributes['club_id'])) {
                       return false;
                   }
                   $membership = UserMembership::where('user_id', $user->id)->whereHas('membership', function ($query) {
                       $query->where('club_id', $this->attributes['club_id']);
                   })->first();
               } else {
                   $membership = null;
               }
                if ($membership) {
                    $membership = $membership->membership;
                } else {
                    $membership = Membership::where('club_id', $this->attributes['club_id'])->where('basic', 1)->first();
                }
                if ($membership) {
                    return $membership->reservation_prepayment;
                }
                return false;
            }
        );
    }

    public function weatherUpdates() {
        return $this->hasMany(CourtWeatherUpdate::class);
    }
}
