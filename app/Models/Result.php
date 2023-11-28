<?php

namespace App\Models;

use App\Actions\CalculateELO;
use App\Actions\ScoreGame;
use App\Jobs\ProcessPowers;
use App\Models\League\Group;
use App\Notifications\AdminError;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Result
 *
 * @property int $id
 * @property array $sets
 * @property \Carbon\Carbon|null $date
 * @property int|null $court_id
 * @property string|null $non_member
 * @property string|null $non_court
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $official
 * @property int|null $rated
 * @property int|null $verified
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \App\Models\Surface|null $court
 * @property-read mixed $comment_count
 * @property-read mixed $link
 * @property-read mixed $winner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereNonCourt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereNonMember($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereOfficial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereRated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereSets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Result whereVerified($value)
 * @mixin \Eloquent
 * @property int|null $surface_id
 * @property string|null $verified_at
 * @property float|null $points
 * @property int|null $club_id
 * @property int|null $game_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read int|null $comments_count
 * @property-read \App\Models\Game|null $game
 * @property-read int|null $players_count
 * @property-read \App\Models\Surface|null $surface
 * @method static \Illuminate\Database\Eloquent\Builder|Result newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result query()
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereSurfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereVerifiedAt($value)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $live
 * @property array|null $live_data
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @method static \Illuminate\Database\Query\Builder|Result onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereLiveData($value)
 * @method static \Illuminate\Database\Query\Builder|Result withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Result withoutTrashed()
 */
class Result extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $appends = ['link', 'winner', 'comment_count'];

    protected $casts = ['sets' => 'array', 'live_data' => 'array', 'date' => 'datetime'];

    protected $with = ['surface', 'players', 'teams'];

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $guarded = [];

    protected static function booted()
    {
        static::deleted(function (Result $result) {
            if ($result->game_id) {
                $game = Game::find($result->game_id);
                ScoreGame::unscore($game);
                if ($game->type_type) {
                    if ($game->type_type === Group::class && $game->type->league && $game->type->league->classification === 'elo'){
                        $game->delete();
                    } else {
                        $game->played_at = null;
                        $game->save();
                    }
                }
            }
        });
    }

    public function surface()
    {
        return $this->belongsTo('App\Models\Surface', 'surface_id');
    }

    public function players()
    {
        return $this->morphedByMany(Team::class, 'participant', 'result_participant')->withPivot('verified')->withTrashed();
    }

    public function teams()
    {
        return $this->morphedByMany(Team::class, 'participant', 'result_participant')->withPivot('verified')->withTrashed();
    }

    public function updateELO()
    {

        //load players to make sure we have proper data
        $this->load('players');
        if ($this->players[0]->rating && $this->players[1]->rating) {
            $calculateELO = new CalculateELO();
            $points = $calculateELO->execute($this->players[0]->rating, $this->players[1]->rating, $this->winner);
            $this->players[0]->rating = $points[0];
            $this->players[0]->save();
            $this->players[1]->rating = $points[1];
            $this->players[1]->save();
            $this->verified = true;
            $this->rated = true;
            $this->points = $points[2];
            if (!$this->verified_at) {
                $this->verified_at = Carbon::now();
            }
            $this->save();
            $clubs1 = $this->players[0]->clubs()->pluck('clubs.id');
            $clubs2 = $this->players[1]->clubs()->pluck('clubs.id');
            foreach ($clubs1->intersect($clubs2) as $id) {
                $r1 = DB::table('club_team')
                    ->where('club_id', $id)
                    ->where('team_id', $this->players[0]->id)
                    ->first();
                $r2 = DB::table('club_team')->where('club_id', $id)->where('team_id', $this->players[1]->id)->first();
                $points = $calculateELO->execute($r1->rating, $r2->rating, $this->winner);
                DB::table('club_team')
                    ->where('club_id', $id)
                    ->where('team_id', $this->players[0]->id)
                    ->update(['rating' => $points[0]]);
                DB::table('club_team')
                    ->where('club_id', $id)->where('team_id', $this->players[1]->id)
                    ->update(['rating' => $points[1]]);
            }
            //ProcessPowers::dispatch();
        } else {
            //notify admin of possible error
            //User::find(1)->notify(new AdminError($this, 'no ratings'));
        }
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }

    public function getCommentCountAttribute()
    {
        $count = Cache::get('comment_count_'.$this->id);
        if (! $count) {
            $count = $this->comments->count();
            Cache::put('comment_count_'.$this->id, $count, 60);
        }

        return $count;
    }

    public function getLinkAttribute()
    {
        return '/result/'.$this->id;
    }

    public function getWinnerAttribute()
    {
        $p1 = 0;
        $p2 = 0;
        if (! $this->sets) {
            return 0;
        }
        foreach ($this->sets as $key => $set) {
            if ($key === 'tie_break') {
                continue;
            }
            if ($set[0] > $set[1]) {
                $p1++;
            }
            if ($set[0] < $set[1]) {
                $p2++;
            }
        }

        return $p1 < $p2;
    }

    public function getDateAttribute($value)
    {
        if (! $value) {
            return $this->created_at;
        }

        return $value;
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function sport() {
        return $this->belongsTo(Sport::class);
    }
}
