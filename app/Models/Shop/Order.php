<?php

namespace App\Models\Shop;

use App\Models\Club;
use App\Models\User;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shop\Order
 *
 * @property-read User|null $buyer
 * @property-read Club $club
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop\OrderItem[] $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 * @mixin \Eloquent
 */
class Order extends Model
{
    use SoftDeletes;

    protected $table = 'shop_orders';

    protected $guarded = [];

    public function buyer() {
        return $this->belongsTo(User::class);
    }

    public function creator() {
        return $this->belongsTo(User::class);
    }

    public function club() {
        return $this->belongsTo(Club::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function workOrders() {
        return $this->morphMany(WorkOrder::class, 'orderable');
    }

    public function scopeCreatedBetween(Builder $builder, $value1, $value2 = null) {
        if (!$value2) {
            $value2 = 'now';
        }
        return $builder->whereBetween('created_at',  [ Carbon::parse($value1), Carbon::parse($value2) ]);
    }
}
