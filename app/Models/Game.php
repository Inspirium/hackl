<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property int|null $type_id
 * @property string|null $type_type
 * @property int|null $court_id
 * @property \Illuminate\Support\Carbon|null $played_at
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $is_surrendered
 * @property-read \App\Models\Court|null $court
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @property-read int|null $players_count
 * @property-read \App\Models\Result|null $result
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Team[] $teams
 * @property-read int|null $teams_count
 * @property-read Model|\Eloquent $type
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereIsSurrendered($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game wherePlayedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTypeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereVerifiedAt($value)
 * @mixin \Eloquent
 * @property int|null $order
 * @property int|null $admin
 * @property int|null $player
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $live
 * @property-read \App\Models\Reservation|null $reservation
 * @method static \Illuminate\Database\Query\Builder|Game onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game wherePlayer($value)
 * @method static \Illuminate\Database\Query\Builder|Game withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Game withoutTrashed()
 */
class Game extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $casts = ['played_at' => 'datetime', 'verified_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected $with = ['players', 'result'];

    protected $guarded = [];

    public function type()
    {
        return $this->morphTo();
    }

    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function players()
    {
        return $this->morphedByMany(Team::class, 'participant')->withPivot(['player', 'admin', 'order'])->withPivotValue('player', 1)->orderBy('order');
    }

    public function teams()
    {
        return $this->morphedByMany(Team::class, 'participant')->withPivot(['player', 'admin', 'order'])->withPivotValue('player', 1)->orderBy('order');;
    }

    public function result()
    {
        return $this->hasOne(Result::class);
    }

    public function getWinner() {
        if ($this->result) {
            return $this->result->players[$this->result->winner];
        }
        if ($this->is_surrendered === 1) {
            return $this->players[0];
        }
        if ($this->is_surrendered === 2) {
            return $this->players[1];
        }
    }

    public function getLoser() {
        if ($this->result) {
            return $this->result->players[!$this->result->winner];
        }
        if ($this->is_surrendered === 1) {
            return $this->players[1];
        }
        if ($this->is_surrendered === 2) {
            return $this->players[0];
        }
    }
}
