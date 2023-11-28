<?php

namespace App\Models;

use App\Models\Shop\Order;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
//use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class User
 *
 * @property $email
 * @property $display_name
 * @property $first_name
 * @property $last_name
 * @property $password
 * @property $gender
 * @property $phone
 * @property $address
 * @property $postal_code
 * @property $city
 * @property $county
 * @property $country
 * @property $image
 * @property int $id
 * @property int|null $birthyear
 * @property float|null $rating
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $remember_token
 * @property float|null $power_club
 * @property float|null $power_global
 * @property int|null $rank_club
 * @property int|null $rank_global
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Club[] $administered
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Club[] $clubs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Court[] $favorite_courts
 * @property-read mixed $club_member
 * @property-read mixed $is_admin
 * @property-read mixed $link
 * @property-read mixed $power
 * @property-read mixed $range
 * @property-read mixed $rounded_power
 * @property-read mixed $score
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\Club $primary_club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reservation[] $reservations
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Result[] $results
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Thread[] $threads
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereBirthyear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCounty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePowerClub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePowerGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRankClub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRankGlobal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $primary_club_id
 * @property string|null $birth_date
 * @property-read int|null $administered_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read int|null $clients_count
 * @property-read int|null $clubs_count
 * @property-read int|null $favorite_courts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FireBaseSubscription[] $fireBaseSubscriptions
 * @property-read int|null $fire_base_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game[] $games
 * @property-read int|null $games_count
 * @property-read mixed $is_trainer
 * @property-read mixed $token
 * @property-read int|null $notifications_count
 * @property-read int|null $reservations_count
 * @property-read int|null $results_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSocial[] $social
 * @property-read int|null $social_count
 * @property-read int|null $threads_count
 * @property-read int|null $tokens_count
 * @property-read \App\Models\Trainer|null $trainer
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePrimaryClubId($value)
 * @property array|null $notifications_settings
 * @property bool|null $is_doubles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Membership[] $memberships
 * @property-read int|null $memberships_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Wallet[] $wallets
 * @property-read int|null $wallets_count
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsDoubles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNotificationsSettings($value)
 */
class User extends Authenticatable implements Auditable, HasLocalePreference
{
    use HasApiTokens, Notifiable, SoftDeletes, CanResetPassword, \OwenIt\Auditing\Auditable;

    protected $guarded = [
        'created_at', 'updated_at', 'deleted_at', 'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'is_admin', 'created_at', 'updated_at', 'deleted_at',
    ];

    protected $casts = [
        'phone' => 'array',
        'notifications_settings' => 'array',
        'is_doubles' => 'boolean',
        'hidden_fields' => 'array',
        'hidden_notifications' => 'array',
    ];

    protected $appends = ['display_name', 'club_member'];

    public function preferredLocale()
    {
        return 'en';
    }

    public function primary_club()
    {
        return $this->belongsTo('App\Models\Club', 'primary_club_id');
    }

    public function favorite_courts()
    {
        return $this->hasMany('App\Models\Court');
    }

    public function getImageAttribute($value)
    {
        if ($value && trim($value) && \Storage::disk('public')->exists($value)) {
            return url('storage/'.$value);
        }
        if ($this->email) {
            return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=200&d=robohash';
        }

        return asset('/images/user.svg');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'player_team', 'player_id', 'team_id');
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_user', 'player_id', 'club_id')->using(ClubMembership::class)->withPivot(['rank', 'power', 'status', 'messages', 'cashier'])->withTimestamps();
    }

    public function memberships()
    {
        return $this->belongsToMany(Membership::class, 'user_membership')->using(UserMembership::class);
    }

    public function results()
    {
        return $this->morphToMany(Result::class, 'participant', 'result_participant')->withPivot('verified')->orderBy('created_at', 'desc');
    }

    public function administered()
    {
        return $this->belongsToMany('App\Models\Club', 'club_admin', 'user_id', 'club_id')->withPivot('level');
    }

    public function reservations()
    {
        return $this->belongsToMany('App\Models\Reservation', 'users_reservations', 'user_id', 'reservation_id')->withPivot('status');
    }

    public function threads()
    {
        return $this->belongsToMany('App\Models\Thread', 'threads_players', 'player_id', 'thread_id')->withPivot(['owner']);
    }

    public function games()
    {
        return $this->morphToMany(Game::class, 'participant', 'game_participants')
            ->withPivot(['order', 'winner']);
    }

    public function getScoreAttribute()
    {
        return round($this->rating);
    }

    public function getRangeAttribute()
    {
        $age = (date('Y') - intval($this->birthyear)) / 10;
        $bottom = floor($age) * 10;
        $top = $bottom + 10;

        return $bottom.'-'.$top;
    }

    public function getPowerAttribute()
    {
        $power = 0;
        if (request()->get('club')) {
            $power = Cache::get('power_club_'.$this->id, 0);
            if (! $power) {
                $power = DB::table('club_user')->where('club_id', request()->get('club')->id)->where('player_id', $this->id)->first(['power']);
                if ($power) {
                    $power = $power->power;
                } else {
                    $power = 0;
                }
                Cache::set('power_club_'.$this->id, $power, 3600);
            }
        }

        return $power;
    }

    public function getRoundedPowerAttribute()
    {
        if ($this->power == 100) {
            return 100;
        }

        return floor($this->power / 10) * 10 + 10;
    }

    public function getLinkAttribute()
    {
        return '/player/'.$this->id;
    }

    public function getDisplayNameAttribute()
    {
        if ($this->is_doubles) {
            return $this->first_name.' - '.$this->last_name;
        }

        return $this->first_name.' '.$this->last_name;
    }

    public function clearCache() {
        Cache::delete('club_member_'.$this->id);
        Cache::delete('club_rank_'.$this->id);
        Cache::delete('power_club_'.$this->id);
    }

    public function getClubMemberAttribute()
    {
        $club_member = Cache::get('club_member_'.$this->id, []);
        $club_member = false;
        if (!$club_member) {
            $this->load('clubs');
            $club_member = $this->clubs->mapWithKeys(function ($item) {
                /** @var Club $item */
                return [$item->id => [
                    'type' => $item->pivot->status,
                    'name' => $item->name,
                    'logo' => $item->logo,
                    'link' => $item->domain,
                    'message' => $item->pivot->messages && isset($item->pivot->messages[$item->pivot->status]) ? $item->pivot->messages[$item->pivot->status]:'',
                    'cashier' => $item->pivot->cashier,
                    'admin' => request()->get('club')?$this->administered->contains(request()->get('club')->id):false,
                ]];
            });
            Cache::set('club_member_'.$this->id, $club_member, 3600);
        }

        return $club_member;
    }

    public function getIsAdminAttribute()
    {
        if (in_array($this->id, [1, 2])) { //super admins
            return Club::get(['id'])->map(fn ($i) => $i->id);
        }
        $this->load('administered');
        if (!$this->administered) {
            return [];
        }
        return $this->administered->map(function ($item) {
            return $item->id;
        });
    }

    public function getRankClubAttribute()
    {
        $club_rank = Cache::get('club_rank_'.$this->id, false);
        if ($club_rank === false) {
            if (request()->get('club')) {
                $club = request()->get('club')->id;
                foreach ($this->clubs as $one) {
                    if ($one->id == $club) {
                        $club_rank = $one->pivot->rank;
                        break;
                    }
                }
                Cache::set('club_rank_'.$this->id, $club_rank, 3600);
            }
        }

        return $club_rank;
    }

    public function getWinsLosses()
    {
        $out = Cache::get('winslosses-'.$this->id);
        if (! $out) {
            $out = ['wins' => 0, 'losses' => 0];
            $t = $this->teams()->where('number_of_players', 1)->first();
            if (!$t) {
                return $out;
            }
            foreach ($t->results as $result) {
                if ($result->non_member) {
                    continue;
                }
                $players = DB::table('result_participant')->where('result_id', $result->id)->get(['participant_id']);

                if (($result->winner && $t->id === $players[1]->participant_id) || (! $result->winner && $t->id === $players[0]->participant_id)) {
                    $out['wins']++;
                } else {
                    $out['losses']++;
                }
            }
            Cache::put('winslosses-'.$this->id, $out, 60);
        }

        return $out;
    }

    public function getStats()
    {
        $out = Cache::get('stats-'.$this->id);
        if (! $out) {
            $results = $this->results;
            $stats = [
                'wins' => 0,
                'sets' => 0,
                'points' => 0,
                'tie_breaks' => 0,
                'first_set_wins' => 0,
            ];
            $totals = [
                'total' => $results->count(),
                'competition' => 0,
                'friendly' => 0,
                'sets' => 0,
                'points' => 0,
                'tie_breaks' => 0,
            ];
            foreach ($results as $result) {
                if (! $result->verified_at) {
                    continue;
                }
                /** @var Result $result */
                $result->load('players');
                $p = 1;
                if ($result->players[0]->id === $this->id) {
                    $p = 0;
                }

                if ($result->game && in_array($result->game->type_type, ['App\Models\League\Group', 'App\Models\TournamentRound'])) {
                    $totals['competition']++;
                } else {
                    $totals['friendly']++;
                }
                if ($result->winner == $p) {
                    $stats['wins']++;
                }
                foreach ($result->sets as $key => $set) {
                    if ($key === 'tie_break') {
                        continue;
                    }
                    if (! $set[0] && ! $set[1]) {
                        continue;
                    }
                    $totals['sets']++;
                    if ($set[0] > $set[1] && ! $p) {
                        $stats['sets']++;
                        if ($key === 0 && $result->players[0]->id === $this->id) {
                            $stats['first_set_wins']++;
                        }
                    }
                    if ($set[0] < $set[1] && $p) {
                        $stats['sets']++;
                        if ($key === 0 && $result->players[1]->id === $this->id) {
                            $stats['first_set_wins']++;
                        }
                    }
                    $stats['points'] += $set[$p];
                    $totals['points'] += $set[0];
                    $totals['points'] += $set[1];
                }
                if (isset($result->sets['tie_break'])) {
                    foreach ($result->sets['tie_break'] as $set) {
                        if (! $set[0] && ! $set[1]) {
                            continue;
                        }
                        if ($set[0] > $set[1] && ! $p) {
                            $stats['tie_breaks']++;
                        }
                        if ($set[0] < $set[1] && $p) {
                            $stats['tie_breaks']++;
                        }
                        $stats['points'] += $set[$p];
                        $totals['points'] += $set[0];
                        $totals['points'] += $set[1];

                        $totals['tie_breaks']++;
                    }
                }
            }
            $out = ['stats' => $stats, 'total' => $totals];
            Cache::put('stats-'.$this->id, $out, 3600);
        }

        return $out;
    }

    /**
     * @param  Club  $club
     * @return int
     */
    public function getClubAdminLevel($club)
    {
        if ($this->isSuperAdmin()) {
            return 3;
        }
        $level = $club->admins()->where('user_id', $this->id)->first(['level']);
        if (! $level) {
            return 0;
        }
        switch ($level->level) {
            case 'owner':
                return 2;
            case 'admin':
                return 1;
            default:
                return 0;
        }
    }

    public function isSuperAdmin()
    {
        return in_array($this->id, [1, 2]);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify((new ResetPasswordNotification($token, $this->clubs[0]))->locale($this->lang));
    }

    public function social()
    {
        return $this->hasMany(UserSocial::class, 'user_id', 'id');
    }

    public function hasSocialLinked($service)
    {
        return (bool) $this->social->where('service', $service)->count();
    }

    public function getTokenAttribute()
    {
        return $this->createToken('')->accessToken;
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class);
    }

    public function getIsTrainerAttribute()
    {
        $trainer = $this->trainer()->first('id');
        if ($trainer) {
            return $trainer->id;
        }

        return null;
    }

    public function fireBaseSubscriptions()
    {
        return $this->hasMany(FireBaseSubscription::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->fireBaseSubscriptions->map(function ($i) {
        return $i->token;
        })->toArray();
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'owner_id');
    }

    public function wallet()
    {
        return [];
        return $this->hasOne(Wallet::class, 'owner_id')->where('club_id', request()->get('club')->id);
    }

    public function getMembership($club = null)
    {
        if (!$club) {
            $club = request()->get('club');
        }
        if (!$club) {
            return null;
        }
        $um = UserMembership::where('user_id', $this->id)->whereHas('membership', function ($query) use ($club) {
            $query->where('club_id', $club->id);
        })->first();
        if ($um) {
            return $um->membership;
        } else {
            // get club basic
            return Membership::where('club_id', $club->id)->where('basic', 1)->first();
        }
    }

    public function primaryTeam() {
        return $this->teams()->where('number_of_players', 1)->where('primary_contact_id', $this->id)->first();
    }

    public function parents() {
        return $this->belongsToMany(User::class, 'parents', 'child_id', 'parent_id');
    }

    public function children() {
        return $this->belongsToMany(User::class, 'parents', 'parent_id', 'child_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function getHiddenFieldsAttribute($value) {
        if ($value) {
            return json_decode($value);
        }
        return [
            'hide_my_phone' => false,
        ];
    }

    public function companies() {
        return $this->belongsToMany(Company::class, 'company_user', 'user_id', 'company_id');
    }
}
