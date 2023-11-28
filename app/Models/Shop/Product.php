<?php

namespace App\Models\Shop;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Club;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shop\Product
 *
 * @property-read Club $club
 * @property-read mixed $main_image
 * @property-read \Illuminate\Database\Eloquent\Collection|Media[] $images
 * @property-read int|null $images_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SoftDeletes;

    protected $table = 'shop_products';

    protected $guarded = [];

    protected $appends = ['main_image'];

    protected $casts = [
        'special' => 'boolean',
        'waiting_list' => 'boolean',
        //'wish_list' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function club() {
        return $this->belongsTo(Club::class);
    }

    public function images() {
        return $this->morphToMany(Media::class, 'imageable', 'media_imageable')->withPivot(['main']);
    }

    public function getMainImageAttribute() {
        $image = $this->images()->wherePivot('main', 1)->first();
        if ($image) {
            return $image;
        }
        return [
            'link' => '/product_placeholder.png',
            'title' => 'placeholder'
        ];
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    protected function wishList(): Attribute {
        return Attribute::make(
            get: function($value) {
                if (!$value) {
                    return [];
                }
                $ids = json_decode($value, true);
                if (!$ids) {
                    return [];
                }
                $users = User::whereIn('id', $ids)->get();
                return UserCollection::make($users)->toArray(request());
                },
            set: fn(array $value) => json_encode($value)
        );
    }
}
