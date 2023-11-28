<?php

namespace App\Models\League;

use App\Models\Game;
use App\Models\League;
use App\Models\Team;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\League\Group
 *
 * @property int $id
 * @property int $league_id
 * @property int|null $order
 * @property int|null $move_down
 * @property int|null $move_up
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $players_in_group
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Game[] $games
 * @property-read int|null $games_count
 * @property-read League $league
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $players
 * @property-read int|null $players_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereLeagueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereMoveDown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereMoveUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group wherePlayersInGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'league_groups';

    protected $guarded = [];

    protected $hasOrder = true;

    protected $with = ['players', 'thread'];

    protected static function booted()
    {
        static::created(function(Group $group){
            $league = $group->league;
            if ($league->classification == 'pyramid') {
                $t = new Thread([
                    'title' => __('titles.new_league_group_thread_title', ['name' => $league->name . ' - ' . $group->name]),
                    'club_id' => $league->club_id,
                ]);
                $t->threadable()->associate($group);
                $t->save();
            }
        });
    }

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function players()
    {
        return $this->belongsToMany(Team::class, 'league_group_player', 'league_group_id', 'player_id')
            ->withPivot(['score', 'player', 'set_diff', 'point_diff', 'freeze'])
            ->withTimestamps()
            ->wherePivot('player', 1)
            ->orderBy('position', 'asc');
    }

    public function games()
    {
        return $this->morphMany(Game::class, 'type');
    }

    public function stats()
    {
        $out = \Cache::get('stats_group' . $this->id, []);
        //$out = [];
        if (empty($out)) {
            foreach ($this->players as $player) {
                $a = [
                    'matches' => [
                        'wins' => 0,
                        'losses' => 0,
                        'total' => 0,
                    ],
                    'sets' => [
                        'wins' => 0,
                        'losses' => 0,
                    ],
                    'points' => [
                        'wins' => 0,
                        'losses' => 0,
                    ],
                ];
                $games = Game::where('type_id', $this->id)->where('type_type', Group::class)->whereHas('players', function ($q) use ($player) {
                    $q->where('participant_id', $player->id);
                })->get();
                foreach ($games as $game) {
                    if (in_array($game->is_surrendered, [1, 2])) {
                        $a['matches']['total']++;
                        if ($game->players[0]->id == $player->id) {
                            $p = 2;
                        } else {
                            $p = 1;
                        }
                        if ($game->is_surrendered == $p) {
                            $a['matches']['wins']++;
                        } else {
                            $a['matches']['losses']++;
                        }
                        continue;
                    }
                    $result = $game->result;
                    if ($result) {
                        if ($result->players[0]->id == $player->id) {
                            $p = 0;
                        } else {
                            $p = 1;
                        }
                        if ($result->winner === $p) {
                            $a['matches']['wins']++;
                        } else {
                            $a['matches']['losses']++;
                        }
                        $a['matches']['total']++;

                        foreach ($result->sets as $key => $set) {
                            if ($key === 'tie_break') {
                                continue;
                            }
                            if ($set[0] > $set[1]) {
                                if ($p === 0) {
                                    $a['sets']['wins']++;
                                } else {
                                    $a['sets']['losses']++;
                                }
                            }
                            if ($set[0] < $set[1]) {
                                if ($p === 1) {
                                    $a['sets']['wins']++;
                                } else {
                                    $a['sets']['losses']++;
                                }
                            }
                            $a['points']['wins'] += $set[$p];
                            $a['points']['losses'] += $set[! $p];
                        }
                    }
                }
                $out[$player->id] = $a;
            }
            \Cache::put('stats_group'.$this->id, $out, 24 * 3600);
        }

        return $out;
    }

    public function thread() {
        //if ($this->league->type == 'pyramid') {
            return $this->morphOne(Thread::class, 'threadable');
        //}
        //return $this->league->thread;
    }

    public function getTotalMatchesAttribute()
    {
        return $this->games->count();
    }

    public function getTotalMatchesPlayedAttribute()
    {
        return $this->games->whereNotNull('played_at')->count();
    }

    public function getTotalMatchesSurrenderedAttribute()
    {
        return $this->games->whereNull('played_at')->count();
    }
}
