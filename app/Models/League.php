<?php

namespace App\Models;

use App\Models\League\Group;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\League
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $type
 * @property int|null $club_id
 * @property int|null $number_of_groups
 * @property int|null $players_in_groups
 * @property int|null $rounds_of_play
 * @property int|null $points
 * @property int|null $move_up
 * @property int|null $move_down
 * @property string|null $playing_sets
 * @property string|null $game_in_set
 * @property string|null $last_set
 * @property \Illuminate\Support\Carbon|null $finish_date
 * @property \Illuminate\Support\Carbon|null $finish_onboarding
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $status
 * @property int|null $points_loser
 * @property int|null $boarding_type
 * @property int|null $points_match
 * @property int|null $parent_id
 * @property string|null $classification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $admins
 * @property-read int|null $admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read League|null $child
 * @property-read \App\Models\Club|null $club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document[] $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read int|null $groups_count
 * @property-read League|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @property-read int|null $players_count
 * @method static \Illuminate\Database\Eloquent\Builder|League newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|League newQuery()
 * @method static \Illuminate\Database\Query\Builder|League onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|League query()
 * @method static \Illuminate\Database\Eloquent\Builder|League whereBoardingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereFinishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereFinishOnboarding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereGameInSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereLastSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereMoveDown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereMoveUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereNumberOfGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League wherePlayersInGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League wherePlayingSets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League wherePointsLoser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League wherePointsMatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereRoundsOfPlay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|League whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|League withTrashed()
 * @method static \Illuminate\Database\Query\Builder|League withoutTrashed()
 * @mixin \Eloquent
 * @property bool|null $is_doubles
 * @property-read mixed $link
 * @method static \Illuminate\Database\Eloquent\Builder|League whereIsDoubles($value)
 */
class League extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'is_doubles' => 'boolean',
        'show_on_tenisplus' => 'boolean',
        'finish_onboarding' => 'datetime',
        'finish_date' => 'datetime',
        'start_date' => 'datetime',
        'show_in_club' => 'boolean',
    ];

    protected $appends = ['link'];

    protected static function booted()
    {
        static::created(function(League $league){
            $t = new Thread([
                'title' => __('titles.new_league_thread_title', ['name' => $league->name]),
                'club_id' => $league->club_id,
            ]);
            $t->threadable()->associate($league);
            $t->save();
        });
    }

    public function players()
    {
        return $this->belongsToMany(Team::class, 'league_player', 'league_id', 'user_id')->withPivot(['admin', 'player', 'rank', 'score'])->wherePivot('player', '=', 1)->withTimestamps();
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'league_player', 'league_id', 'user_id')->withPivot(['admin', 'player', 'rank', 'score'])->wherePivot('admin', '=', 1)->withTimestamps();
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class)->orderBy('order', 'ASC');
    }

    public function documents()
    {
        return $this->morphToMany(Document::class, 'owner', 'document_owners');
    }

    public function parent()
    {
        return $this->belongsTo(League::class);
    }

    public function child()
    {
        return $this->hasOne(League::class, 'parent_id', 'id');
    }

    public function getClassificationAttribute($value)
    {
        if (! $value) {
            return 'elo';
        }

        return $value;
    }

    public function getLinkAttribute()
    {
        return '/liga/'.$this->id;
    }

    public function thread() {
        return $this->morphOne(Thread::class, 'threadable');
    }

    public function getTotalMatchesAttribute()
    {
        $t = Cache::get('league_'.$this->id.'_total_matches');
        if (!$t) {
            $t = $this->groups->sum('total_matches');
            Cache::put('league_'.$this->id.'_total_matches', $t, 3600*24);
        }
        return $t;
    }

    public function getTotalMatchesPlayedAttribute()
    {
        $t = Cache::get('league_'.$this->id.'_total_matches_played');
        if (!$t) {
            $t = $this->groups->sum('total_matches_played');
            Cache::put('league_'.$this->id.'_total_matches_played', $t, 3600*24);
        }
        return $t;
    }

    public function getTotalMatchesNotPlayedAttribute()
    {
        $t = Cache::get('league_'.$this->id.'_total_matches_not_played');
        if (!$t) {
            $t = $this->groups->sum('total_matches_not_played');
            Cache::put('league_'.$this->id.'_total_matches_not_played', $t, 3600*24);
        }
        return $t;
    }
}
