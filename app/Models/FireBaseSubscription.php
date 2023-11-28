<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FireBaseSubscription
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $club_id
 * @property string|null $token
 * @property string|null $platform
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Club|null $club
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FireBaseSubscription whereUserId($value)
 * @mixin \Eloquent
 */
class FireBaseSubscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
