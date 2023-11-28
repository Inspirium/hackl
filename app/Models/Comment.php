<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $message
 * @property int $player_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $multimedia
 * @property string|null $multimedia_type
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\Models\User $player
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereMultimedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereMultimediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 */
class Comment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'comments';

    protected $with = ['player'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function player()
    {
        return $this->belongsTo('App\Models\User', 'player_id');
    }
}
