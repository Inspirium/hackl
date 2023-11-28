<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlayerProfile
 *
 * @property int $id
 * @property int $player_id
 * @property int $owner_id
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\User $player
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlayerProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlayerProfile extends Model
{
    use HasFactory;

    protected $fillable = ['data', 'player_id', 'owner_id'];

    protected $casts = [
        'data' => 'array'
    ];

    public function player() {
        return $this->belongsTo(User::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }
}
