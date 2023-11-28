<?php

namespace App\Http\Controllers\V2\Shop;

use App\Http\Controllers\V2\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Shop\Order;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AssignerController extends Controller {

    public function index() {
        $orders = QueryBuilder::for(WorkOrder::class)
            ->allowedFilters([
                'status',
                AllowedFilter::callback('club', function ($query, $value) {
                    $query->whereHas('orderable', function ($query) use ($value) {
                        $query->where('club_id', $value);
                    });
                })->default(\request()->get('club')->id),
                AllowedFilter::callback('special', function ($query, $value) {
                    $query->whereHas('orderable', function ($query) use ($value) {
                        $query->whereHas('items', function ($query) use ($value) {
                            $query->whereHas('product', function ($query) use ($value) {
                                $query->where('special', $value);
                            });
                        });
                    });
                }),
                AllowedFilter::scope('created_between'),
            ])
            ->with('assigner')
            ->select('work_orders.*',  DB::raw('COUNT(work_orders.id) as work_orders_count'))
            ->groupBy('work_orders.assigner_id')
            ->orderBy('work_orders_count', 'desc')
            ->paginate(\request()->input('limit'))
            ->appends(\request()->query());
        $buyers = [];
        foreach ($orders as $order) {
            $buyer = $order->assigner;
            $buyer->work_orders_count = $order->work_orders_count;
            $buyers[] = $buyer;
        }
        return UserCollection::make($buyers);
    }
}
