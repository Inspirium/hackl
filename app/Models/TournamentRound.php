<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TournamentRound
 *
 * @property int $id
 * @property int|null $tournament_id
 * @property string|null $marker
 * @property int|null $order
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Game[] $games
 * @property-read int|null $games_count
 * @property-read \App\Models\Tournament|null $tournament
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound query()
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound whereMarker($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound whereTournamentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TournamentRound whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TournamentRound extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function games()
    {
        return $this->morphMany(Game::class, 'type');
    }
}
