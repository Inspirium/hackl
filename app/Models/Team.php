<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Team
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game[] $games
 * @property-read int|null $games_count
 * @property int|null $number_of_players
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $display_name
 * @property string|null $image
 * @property string|null $phone
 * @property string|null $email
 * @property float|null $rank
 * @property int|null $rating
 * @property float|null $power
 * @property int|null $primary_contact_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Club[] $clubs
 * @property-read int|null $clubs_count
 * @property-read mixed $link
 * @property-read mixed $power_club
 * @property-read mixed $rank_club
 * @property-read mixed $rating_club
 * @property-read mixed $rounded_power
 * @property-read mixed $score
 * @property-read mixed $score_club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @property-read int|null $players_count
 * @property-read \App\Models\User|null $primary_contact
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Result[] $results
 * @property-read int|null $results_count
 * @method static \Illuminate\Database\Eloquent\Builder|Team ofSingleUser($user_id)
 * @method static \Illuminate\Database\Query\Builder|Team onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereNumberOfPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team wherePrimaryContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Team withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Team withoutTrashed()
 */
class Team extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $with = ['players', 'primary_contact'];

    protected $appends = ['link'];

    public function getLinkAttribute(){
        return '';
    }

    public function getImageAttribute($value)
    {
        if ($this->number_of_players !==1 && trim($value) && \Storage::disk('public')->exists($value)) {
            return url('storage/'.$value);
        }
        if ($this->primary_contact && $this->primary_contact->image) {
            return $this->primary_contact->image;
        }
        if ($this->email) {
            return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=200&d=robohash';
        }
        if ($this->primary_contact && $this->primary_contact->email) {
            return 'https://www.gravatar.com/avatar/'.md5($this->primary_contact->email).'?s=200&d=robohash';
        }

        return asset('/images/user.svg');
    }

    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_team')->withPivot(['rank', 'power', 'rating'])->withTimestamps();
    }

    public function getScoreAttribute()
    {
        return round($this->rating);
    }

    public function getPowerAttribute()
    {
        if (request()->get('club')) {
            $club = request()->get('club')->id;
            foreach ($this->clubs as $one) {
                if ($one->id == $club) {
                    return $one->pivot->power;
                }
            }
        }

        return 0;
    }

    public function getRoundedPowerAttribute()
    {
        if ($this->power == 100) {
            return 100;
        }

        return floor($this->power / 10) * 10 + 10;
    }

    public function getDisplayNameAttribute($value)
    {
        if ($this->number_of_players !== 1 && $value) {
            return $value;
        }

        if ($this->number_of_players === 1) {
            if ($this->primary_contact) {
                return $this->primary_contact->display_name;
            } else {
                return '';
            }
        }

        return $this->players->map(fn ($i) => $i->last_name)->join(' - ');
    }

    public function games()
    {
        return $this->morphToMany(Game::class, 'participant', 'game_participants')
            ->withPivot(['order', 'winner']);
    }

    public function players()
    {
        return $this->belongsToMany(User::class, 'player_team', 'team_id', 'player_id');
    }

    public function results()
    {
        return $this->morphToMany(Result::class, 'participant', 'result_participant');
    }

    public function primary_contact() {
        return $this->belongsTo(User::class )->withTrashed();
    }

    public function getStats()
    {
        $out = Cache::get('stats-team-'.$this->id);
        //$out = false;
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
            Cache::put('stats-team-'.$this->id, $out, 3600);
        }

        return $out;
    }

    public function getWinsLosses()
    {
        $out = Cache::get('winslosses-team-'.$this->id);
        if (! $out) {
            $out = ['wins' => 0, 'losses' => 0];
            foreach ($this->results as $result) {
                if ($result->non_member) {
                    continue;
                }
                $players = DB::table('result_participant')->where('result_id', $result->id)->get(['participant_id']);

                if (($result->winner && $this->id === $players[1]->participant_id) || (! $result->winner && $this->id === $players[0]->participant_id)) {
                    $out['wins']++;
                } else {
                    $out['losses']++;
                }
            }
            Cache::put('winslosses-team-'.$this->id, $out, 60);
        }

        return $out;
    }

    public function scopeOfSingleUser($query, $user_id) {
        return $query->where('number_of_players', 1)->where('primary_contact_id', $user_id);
    }

    public function getPowerClubAttribute() {
        if (!request()->get('club')) {
            return 0;
        }
        $c = request()->get('club')->id;
        foreach ($this->clubs as $club) {
            if ($club->id === $c) {
                return $club->pivot->power;
            }
        }
        return 0;
    }

    public function getRankClubAttribute() {
        if (!request()->get('club')) {
            return 0;
        }
        $c = request()->get('club')->id;
        foreach ($this->clubs as $club) {
            if ($club->id === $c) {

                return $club->pivot->rank;
            }
        }

        return 0;
    }

    public function getRatingClubAttribute($club_id = null) {
        if (!$club_id) {
            if (!request()->get('club')) {
                return 0;
            }
            $club_id = request()->get('club')->id;
        }
        foreach ($this->clubs as $club) {
            if ($club->id === $club_id) {
                return $club->pivot->rating;
            }
        }
    }

    public function getScoreClubAttribute() {
        return round($this->rating_club);
    }

    public function tournament_scores() {
        $values = false;
        if (request()->has('filter.final_between')) {
            $values = explode(',', request()->input('filter.final_between'));
        }
        $tournament = request()->input('filter.tournament', 0);
        $club = request()->input('filter.club', 0);
        return $this->hasMany(TournamentScore::class, 'player_id')
            ->select('tournament_player.*')
            ->whereNotNull('final_score')
            ->when($values, function($query) use ($values) {
                $query->whereBetween('final_at', [ Carbon::parse($values[0]), Carbon::parse($values[1]) ]);
            })
            ->when($tournament, function($query) use ($tournament) {
                $query->where('tournament_id');
            })
            ->when($club, function($query) use ($club) {
                $query->join('tournaments', 'tournament_player.tournament_id', 'tournaments.id')
                    ->where('tournaments.club_id', $club);
            });
    }

    public function reservations()
    {
        return $this->belongsToMany('App\Models\Reservation', 'users_reservations', 'user_id', 'reservation_id')->withPivot('status');
    }

    public function subscriptions() {
        return $this->belongsToMany(Subscription::class, 'user_subscriptions', 'team_id', 'subscription_id')->using(UserSubscription::class)->withPivot('is_paused', 'price');
    }

    public function getActiveSubscriptionAttribute() {
        if (strpos(request()->path(), 'school_group') !== false) {
            $school_group = request()->route('school_group');
            if (!$school_group) {
                return null;
            }
            $us = UserSubscription::query()
                ->where('team_id', $this->id)
                ->where('subscribable_id', $school_group->id)
                ->where('subscribable_type', SchoolGroup::class)
                ->with('subscription')
                ->first();
            if ($us) {
                $out = JsonResource::make($us->subscription);
                if ($us->price) {
                    $out['price'] = $us->price;
                }
                $out['pivot_id'] = $us->id;
                $out['is_paused'] = $us->is_paused;
                return $out;
            }
        }

        return null;
    }

    public function school_groups() {
        return $this->belongsToMany(SchoolGroup::class, 'school_group_user', 'team_id', 'school_group_id');
    }
}
