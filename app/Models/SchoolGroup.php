<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SchoolGroup
 *
 * @property int $id
 * @property string $name
 * @property int|null $club_id
 * @property string|null $image
 * @property int|null $trainer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $thread_id
 * @property-read \App\Models\Club|null $club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @property-read int|null $players_count
 * @property-read \App\Models\Thread|null $thread
 * @property-read \App\Models\User|null $trainer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $trainers
 * @property-read int|null $trainers_count
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup newQuery()
 * @method static \Illuminate\Database\Query\Builder|SchoolGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereTrainerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SchoolGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SchoolGroup withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SchoolGroup withoutTrashed()
 * @mixin \Eloquent
 */
class SchoolGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $with = ['trainer'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function players()
    {
        return $this->belongsToMany(Team::class, 'school_group_user', 'school_group_id', 'team_id');
    }

    public function teams() {
        return $this->belongsToMany(Team::class, 'school_group_user');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function trainers()
    {
        return $this->belongsToMany(User::class, 'school_group_trainer');
    }

    public function getImageAttribute($value)
    {
        if (trim($value) && \Storage::disk('public')->exists($value)) {
            return url('storage/'.$value);
        }

        return 'https://www.gravatar.com/avatar/'.md5($this->name.'@'.$this->club->domain).'?s=200&d=robohash';
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function subscriptions() {
        return $this->morphMany(UserSubscription::class, 'subscribable');
    }

    public function locations() {
        return $this->morphToMany(Location::class, 'locationable');
    }
}
