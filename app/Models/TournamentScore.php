<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TournamentScore
 *
 * @property int|null $tournament_id
 * @property int|null $player_id
 * @property string|null $player_type
 * @property int|null $admin
 * @property int|null $player
 * @property int|null $seed
 * @property string|null $final_status
 * @property int $id
 * @property int|null $final_score
 * @property \Illuminate\Support\Carbon|null $final_at
 * @property-read \App\Models\Team|null $team
 * @property-read \App\Models\Tournament|null $tournament
 * @method static Builder|TournamentScore finalBetween($value1, $value2 = null)
 * @method static Builder|TournamentScore newModelQuery()
 * @method static Builder|TournamentScore newQuery()
 * @method static Builder|TournamentScore query()
 * @method static Builder|TournamentScore whereAdmin($value)
 * @method static Builder|TournamentScore whereFinalAt($value)
 * @method static Builder|TournamentScore whereFinalScore($value)
 * @method static Builder|TournamentScore whereFinalStatus($value)
 * @method static Builder|TournamentScore whereId($value)
 * @method static Builder|TournamentScore wherePlayer($value)
 * @method static Builder|TournamentScore wherePlayerId($value)
 * @method static Builder|TournamentScore wherePlayerType($value)
 * @method static Builder|TournamentScore whereSeed($value)
 * @method static Builder|TournamentScore whereTournamentId($value)
 * @mixin \Eloquent
 */
class TournamentScore extends Model
{
    protected $table = 'tournament_player';

    protected $casts = ['final_at' => 'datetime'];

    public function tournament() {
        return $this->belongsTo(Tournament::class);
    }

    public function team() {
        return $this->belongsTo(Team::class, 'player_id');
    }

    public function scopeFinalBetween(Builder $builder, $value1, $value2 = null) {
        if (!$value2) {
            $value2 = 'now';
        }
        return $builder->whereBetween('final_at',  [ Carbon::parse($value1), Carbon::parse($value2) ]);
    }
}
