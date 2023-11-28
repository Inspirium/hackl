<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shop\OrderItem
 *
 * @property-read \App\Models\Shop\Order|null $order
 * @property-read \App\Models\Shop\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Query\Builder|OrderItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Query\Builder|OrderItem withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OrderItem withoutTrashed()
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'shop_order_items';

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
