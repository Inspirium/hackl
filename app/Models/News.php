<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\News
 *
 * @property int $id
 * @property string $title
 * @property string $name
 * @property string $content
 * @property string|null $image
 * @property int|null $user_id
 * @property int|null $club_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $author
 * @property-read \App\Models\Club|null $club
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Query\Builder|News onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|News withTrashed()
 * @method static \Illuminate\Database\Query\Builder|News withoutTrashed()
 * @mixin \Eloquent
 */
class News extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $with = ['author'];

    public function club()
    {
        return $this->belongsTo('App\Models\Club');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getImageAttribute($value)
    {
        if (trim($value)) {
            return url('storage/'.$value);
        }

        return null;
    }
}
