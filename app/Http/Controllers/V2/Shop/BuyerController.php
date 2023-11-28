<?php

namespace App\Http\Controllers\V2\Shop;

use App\Http\Controllers\V2\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Shop\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BuyerController extends Controller {

    public function index() {
        $orders = QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::exact('club', 'club_id')->default(\request()->get('club')->id),
                AllowedFilter::callback('special', function ($query, $value) {
                        $query->whereHas('items', function ($query) use ($value) {
                            $query->whereHas('product', function ($query) use ($value) {
                                $query->where('special', $value);
                            });
                        });
                }),
                AllowedFilter::scope('created_between'),
            ])
            ->with('buyer')
            ->select('shop_orders.*', DB::raw('SUM(shop_orders.total) as orders_total'), DB::raw('COUNT(shop_orders.id) as orders_count'))
            ->groupBy('shop_orders.buyer_id')
            ->orderBy('orders_total', 'desc')
            ->paginate(\request()->input('limit'))
            ->appends(\request()->query());
        $buyers = [];
        foreach ($orders as $order) {
            $buyer = $order->buyer;
            $buyer->orders_total = $order->orders_total;
            $buyer->orders_count = $order->orders_count;
            $buyers[] = $buyer;
        }
        return UserCollection::make($buyers);
    }
}
