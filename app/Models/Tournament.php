<?php

namespace App\Models;

use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Tournament
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $type
 * @property int|null $number_of_players
 * @property int|null $club_id
 * @property string|null $access_level
 * @property string $status
 * @property array|null $data
 * @property string|null $type_of_registration
 * @property \Illuminate\Support\Carbon|null $application_deadline
 * @property \Illuminate\Support\Carbon|null $active_from
 * @property \Illuminate\Support\Carbon|null $active_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $admins
 * @property-read int|null $admins_count
 * @property-read \App\Models\Club|null $club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game[] $games
 * @property-read int|null $games_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @property-read int|null $players_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereAccessLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereActiveFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereActiveTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereApplicationDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereNumberOfPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereTypeOfRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $league_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document[] $documents
 * @property-read int|null $documents_count
 * @property-read mixed $link
 * @property-read mixed $winner_user
 * @property-read \App\Models\League|null $league
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TournamentRound[] $rounds
 * @property-read int|null $rounds_count
 * @method static \Illuminate\Database\Query\Builder|Tournament onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tournament whereLeagueId($value)
 * @method static \Illuminate\Database\Query\Builder|Tournament withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Tournament withoutTrashed()
 */
class Tournament extends Model
{
    use SoftDeletes;

    protected $table = 'tournaments';

    protected $guarded = [];

    protected $appends = ['link', 'winner_user'];

    protected $casts = [
        'data' => 'array',
        'show_on_tenisplus' => 'boolean',
        'application_deadline' => 'datetime',
        'active_from' => 'datetime',
        'active_to' => 'datetime',
        'show_in_club' => 'boolean',
    ];

    protected static function booted()
    {
        static::created(function(Tournament $tournament){
            $t = new Thread([
                'title' => __('titles.new_tournament_thread_title', ['name' => $tournament->name]),
                'club_id' => $tournament->club_id,
            ]);
            $t->threadable()->associate($tournament);
            $t->save();
        });
    }

    public function players()
    {
        return $this->belongsToMany(Team::class, 'tournament_player', 'tournament_id', 'player_id')->withPivot(['admin', 'player', 'seed', 'final_status', 'final_score', 'final_at'])->withPivotValue('player', true);
    }

    public function admins()
    {
         return $this->belongsToMany(User::class, 'tournament_player', 'tournament_id', 'player_id')->withPivot(['admin', 'player'])->withPivotValue('admin', true);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function documents()
    {
        return $this->morphToMany(Document::class, 'owner', 'document_owners');
    }

    public function rounds()
    {
        return $this->hasMany(TournamentRound::class);
    }

    public function getDataAttribute($value)
    {
        $value = json_decode($value, true);
        $out = [
            'playing_sets' => $value['playing_sets'] ?? null,
            'game_in_set' => $value['game_in_set'] ?? null,
            'last_set' => $value['last_set'] ?? null,
            'price' => $value['price'] ?? null,
            'boarding_type' => $value['boarding_type'] ?? null,
            'is_doubles' => $value['is_doubles'] ?? false,
            'winner' => [
                'image' => $value['winner']['image'] ?? null,
                'description' => $value['winner']['description'] ?? null,
            ],
            'cup_scoring' => $value['cup_scoring'] ?? null,
        ];

        return $out;
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function getLinkAttribute()
    {
        return '/cup/'.$this->id;
    }

    public function getWinnerUserAttribute()
    {
        if ($this->id === 155) {
            return UserResource::make(User::find(231));
        }
        if ($this->status == 5) {
            /** @var TournamentRound $round */
            $round = $this->rounds()->orderBy('order', 'desc')->first();
            /** @var Game $game */
            $game = $round->games[0];
            if ($game->is_surrendered) {
                return TeamResource::make($game->players[!($game->is_surrendered - 1)]);
            }
            if ($game->result) {
                return TeamResource::make($game->result->players[$game->result->winner]);
            }
        }

        return null;
    }

    public function games() {
        return $this->hasManyThrough(Game::class, TournamentRound::class, 'tournament_id', 'type_id')
            ->where(
            'type_type',
            TournamentRound::class
            );
    }

    public function thread() {
        return $this->morphOne(Thread::class, 'threadable');
    }
}
