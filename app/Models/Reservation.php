<?php

namespace App\Models;

use App\Exceptions\CourtTimeUnavailable;
use App\Exceptions\PlayerHasTooManyReservations;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Reservation
 *
 * @property int $id
 * @property int|null $court_id
 * @property \Carbon\Carbon|null $from
 * @property \Carbon\Carbon|null $to
 * @property float|null $price
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $approved_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \App\Models\Court|null $court
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $name
 * @property string|null $type
 * @property int|null $school_group_id
 * @property int|null $game_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attendance[] $attendances
 * @property-read int|null $attendances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Payment|null $payment
 * @property-read int|null $players_count
 * @property-read \App\Models\SchoolGroup|null $schoolGroup
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $watchers
 * @property-read int|null $watchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Reservation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereSchoolGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation whereType($value)
 * @method static \Illuminate\Database\Query\Builder|Reservation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Reservation withoutTrashed()
 * @property string|null $payment_invoice
 * @property string|null $payment_note
 * @property int|null $payment_user_id
 * @property string|null $payment_date
 * @property-read \App\Models\Game|null $game
 * @property-read mixed $is_paid
 * @property-read \App\Models\User|null $payment_user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePaymentInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePaymentNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reservation wherePaymentUserId($value)
 */
class Reservation extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $with = ['court', 'game', 'category'];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
        'approved_at' => 'datetime',
        'deleted_at' => 'datetime',
        'needs_payment' => 'boolean',
        'payment_date' => 'datetime',
        'applicant' => 'array'
    ];

    protected static function booted()
    {
        static::creating(function (Reservation $reservation) {
            /*$reservations = Reservation::where('court_id', $reservation->court_id)->whereDate('from', '=', $reservation->from->toDateString())->whereNull('deleted_at')->get();
            $from = $reservation->from;
            $to = $reservation->to;
            foreach ($reservations as $r) {
                if (($r->from->gte($from) && $r->from->lt($to)) || ($from->gte($r->from) && $from->lt($r->to))) {
                    throw new CourtTimeUnavailable();
                }
            }*/
            $club = $reservation->court->club;
            if (Auth::user()->is_admin->contains($club->id)) {
                return true;
            }
            /** @var User $buyer */
            $buyer = request()->get('players');
            if (!$buyer) {
                return true;
            }
            $buyer = $buyer[0];
            if ($buyer['type'] === 'player') {
                $team = Team::query()->where('primary_contact_id', $buyer['id'])
                    ->where('number_of_players', 1)
                    ->first();
            } else {
                $team = Team::find($buyer['id']);
            }
            $buyer = $team->primary_contact;
            if (!$buyer) { // for teams with no primary contact, mostly errors in db
                return true;
            }
                $um = UserMembership::where('user_id', $buyer->id)->whereHas('membership', function ($query) use ($club) {
                    $query->where('club_id', $club->id);
                })->first();
                $um = $um ? $um->membership : null;
                if (!$um) {
                    $um = Membership::where('club_id', $club->id)->where('basic', 1)->first();
                }
                if ($um->max_reservation_per_period) {
                    $reservations = $team->reservations()
                        ->whereHas('court', function ($query) use ($club) {
                            $query->where('club_id', $club->id);
                        })
                        ->whereNull('school_group_id')
                        ->where('from', '>=', Carbon::now())->get();
                    $sum = 0;
                    foreach ($reservations as $r) {
                        if ($r->players[0]->id != $team->id) continue;
                        $sum += $r->to->diffInMinutes($r->from);
                    }
                    $sum += $reservation->to->diffInMinutes($reservation->from);
                    if ($sum > ($um->max_reservation_per_period_days * 60)) {
                        throw new PlayerHasTooManyReservations();
                    }
                }
            return true;
        });

        static::deleted(function(Reservation $reservation) {
            //check if reservation is paid
                $walletTransaction = new \App\Actions\Wallet\WalletTransaction();
                // get payments
                foreach ($reservation->payments as $payment) {
                    if ($payment->paid_at) {
                        $walletTransaction->handle(null, $payment->user, $payment->amount, 'Povrat sredstava za rezervaciju: '. $reservation->from . ' ' . $reservation->court->name, $payment->club);
                    }
                }
        });
    }

    public function players()
    {
        return $this->belongsToMany(Team::class, 'users_reservations', 'reservation_id', 'user_id')->withPivot('status')->withPivotValue('status', 'player')->withTimestamps();
    }

    public function watchers()
    {
        return $this->belongsToMany('App\Models\User', 'users_reservations', 'reservation_id', 'user_id')->withPivot('status')->withPivotValue('status', 'watcher')->withTimestamps();
    }

    public function court()
    {
        return $this->belongsTo('App\Models\Court');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function schoolGroup()
    {
        return $this->belongsTo(SchoolGroup::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function payment_user()
    {
        return $this->belongsTo(User::class, 'payment_user_id');
    }

    public function getIsPaidAttribute()
    {
        return (bool) $this->payment_date;
    }

    public function getPrices() {
        $from = (int)$this->from->format('Hi');
        $to = (int)$this->to->format('Hi');
        $count = 0;
        foreach ($this->players as $team) {
            $count += $team->number_of_players;
        }
        $out = [];
        foreach ($this->players as $team) {
            foreach ($team->players as $player) {
                $price = 0;
                $hours = $this->court->getWorkingHours($this->from->format('Y-m-d'), $player);
                $step = $this->court->reservation_duration / 60;
                foreach ($hours as $one) {
                    $hour = $one['hour'] * 100;
                    if ($hour >= $from && $hour < $to) {
                        $price += $one['price'];
                    }
                }
                $out[$player->id] = $price / $count;
            }
        }
        return $out;
    }

    public function payments() {
        return $this->morphMany(Payment::class, 'type');
    }

    public function category() {
        return $this->belongsTo(ReservationCategory::class);
    }

    public function school_performances() {
        return $this->hasMany(SchoolPerformance::class);
    }

    public function scopeCreatedBetween(Builder $builder, $value1, $value2 = null) {
        if (!$value2) {
            $value2 = 'now';
        }
        return $builder->whereBetween('created_at',  [ Carbon::parse($value1), Carbon::parse($value2) ]);
    }

    public function getCreatedByAttribute() {
        return $this->audits()->where('event', 'created')->first()?->user;
    }

    public function getCanceledByAttribute() {
        return $this->audits()->where('event', 'deleted')->first()?->user;
    }


    // test for prices
    public function getInvoiceAmount() {

    }
}
