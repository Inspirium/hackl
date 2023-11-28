<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Classified
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property float|null $price
 * @property int $user_id
 * @property string $image
 * @property string $category
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $club_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read mixed $link
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Classified whereUserId($value)
 * @mixin \Eloquent
 * @property-read int|null $comments_count
 * @method static \Illuminate\Database\Eloquent\Builder|Classified newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classified newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Classified query()
 * @property-read \App\Models\Club|null $club
 */
class Classified extends Model
{
    protected $table = 'classifieds';

    protected $guarded = [];

    protected $with = ['user'];

    protected $appends = ['link'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }

    public function getImageAttribute($value)
    {
        if (trim($value) && \Storage::disk('public')->exists($value)) {
            return url('storage/'.$value);
        }
        $sub = request()->get('club')->subdomain;
        return "/shop_noimage.jpg";
    }

    public function getLinkAttribute()
    {
        return '/classified/'.$this->id;
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
