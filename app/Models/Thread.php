<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Thread
 *
 * @property int $id
 * @property string|null $title
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $club_id
 * @property int|null $public
 * @property-read mixed $last_message
 * @property-read mixed $link
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Message[] $messages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $players
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Thread onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thread whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thread whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thread wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thread whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Thread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Thread withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Thread withoutTrashed()
 * @mixin \Eloquent
 * @property-read int|null $messages_count
 * @property-read int|null $players_count
 * @method static \Illuminate\Database\Eloquent\Builder|Thread newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Thread query()
 * @property-read \App\Models\Club|null $club
 */
class Thread extends Model
{
    use SoftDeletes;

    protected $table = 'threads';

    protected $with = ['players'];

    protected $guarded = [];

    protected $appends = ['lastMessage', 'link'];

    public function messages()
    {
        return $this->hasMany('App\Models\Message')->orderBy('created_at', 'desc');
    }

    public function players()
    {
        return $this->belongsToMany('App\Models\User', 'threads_players', 'thread_id', 'player_id')->withPivot(['owner']);
    }

    public function getLastMessageAttribute()
    {
        return $this->messages()->whereNull('multimedia_type')->first();
    }

    public function getLinkAttribute()
    {
        return '/messages/'.$this->id;
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function threadable() {
        return $this->morphTo();
    }
}
