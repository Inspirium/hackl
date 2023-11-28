<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property string|null $message
 * @property int $thread_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int $player_id
 * @property string|null $multimedia
 * @property string|null $multimedia_type
 * @property-read \App\Models\User $player
 * @property-read \App\Models\Thread $thread
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereMultimedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereMultimediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Message withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 */
class Message extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'messages';

    protected $with = ['player'];

    public function thread()
    {
        return $this->belongsTo('App\Models\Thread');
    }

    public function player()
    {
        return $this->belongsTo('App\Models\User', 'player_id');
    }

    public function getMultimediaAttribute($value)
    {
        if ($value) {
            return url('storage/'.$value);
        }

        return $value;
    }

    public function getMessageAttribute($value)
    {
        if ($r = json_decode($value, true)) {
            return $r;
        }

        return $value;
    }
}
