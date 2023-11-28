<?php

namespace App\Http\Controllers\V2\Shop;

use App\Http\Controllers\V2\Controller;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\Shop\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    public function __construct() {
        //$this->middleware('auth:api');
    }

    public function index(Request $request) {
        $orders = QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::callback('special', function($query, $value) {
                    $query->whereHas('items', function($query) use ($value) {
                        $query->whereHas('product', function($query) use ($value) {
                            $query->where('special', $value);
                        });
                    });
                }),
                AllowedFilter::exact('buyer', 'buyer_id'),
                AllowedFilter::exact('creator', 'creator_id'),
                AllowedFilter::exact('club', 'club_id')->default($request->get('club') ? $request->get('club')->id : null),
                AllowedFilter::scope('created_between'),
                AllowedFilter::callback('work_order_status', function($query, $value) {
                    $query->whereHas('workOrders', function($query) use ($value) {
                        $query->where('status', $value);
                    });
                }),
            ])
            ->allowedIncludes(['items', 'items.product', 'buyer', 'creator', 'club',
                AllowedInclude::relationship('work_orders', 'workOrders'),
                AllowedInclude::relationship('work_orders.assignee', 'workOrders.assignee'),
                AllowedInclude::relationship('work_orders.assigner', 'workOrders.assigner'),
            ])
            ->allowedSorts(['created_at'])->defaultSort('-created_at')
            //->select('shop_orders.*', DB::raw('SUM(shop_orders.total) as order_totals'))
            ->paginate($request->input('limit'))
            ->appends($request->query());
        if ($request->input('totals')) {
            $total = QueryBuilder::for(Order::class)
                ->allowedFilters([
                    AllowedFilter::callback('special', function($query, $value) {
                        $query->whereHas('items', function($query) use ($value) {
                            $query->whereHas('product', function($query) use ($value) {
                                $query->where('special', $value);
                            });
                        });
                    }),
                    AllowedFilter::exact('buyer', 'buyer_id'),
                    AllowedFilter::exact('creator', 'creator_id'),
                    AllowedFilter::exact('club', 'club_id')->default($request->get('club')->id),
                    AllowedFilter::scope('created_between'),
                ])
                ->sum('total');
        }
        return JsonResource::collection($orders)->additional([
            'meta' => [
                'order_totals' => $total??null
            ]
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'items' => 'required',
            'wallet' => 'sometimes',
        ]);

        if ($request->has('client.id')) {
            $user = User::find($request->input('client.id'));
        } else {
            $user = \Auth::user();
        }
        $club = $request->get('club');

        $order = Order::create([
            'buyer_id' => $user->id,
            'club_id' => $club->id,
            'total' => 0,
            'tax_total' => 0,
            'creator_id' => \Auth::id()
        ]);

        $total = 0;
        $total_tax = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['id']);
            $quantity = $item['quantity']??1;
            $price = $product->price * $quantity;
            $tax = $product->tax_percent / 100 * $product->price * $quantity;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'tax_total' => $tax,
                'total' => $price,
                'data' => isset($item['data']) ? json_encode($item['data']) : [],
            ]);
            $total += $price;
            $total_tax += $tax;
        }

        $order->total = $total;
        $order->tax_total = $total_tax;
        $order->save();

        //\Notification::send($order->club->admins, new \App\Notifications\Shop\NewOrderCreated($order, $user));
        foreach($order->club->admins as $admin) {
            $admin->notify((new \App\Notifications\Shop\NewOrderCreated($order, $user))->locale($admin->lang));
        }

        /*
         * $createTransaction = new CreateTransaction();
        try {
            $createTransaction->handle($request, $order, $total);
        } catch (\Exception $exception) {
            return response()->json(['error'], 400);
        }
        */

        return JsonResource::make($order);
    }

    public function show(Request $request, $order) {
        $order = QueryBuilder::for(Order::where('id', $order))
            ->allowedIncludes(['items', 'items.product', 'buyer', 'creator', 'club',
                AllowedInclude::relationship('work_orders', 'workOrders'),
                AllowedInclude::relationship('work_orders.assignee', 'workOrders.assignee'),
                AllowedInclude::relationship('work_orders.assigner', 'workOrders.assigner'),

            ])
            ->first();
        return JsonResource::make($order);
    }
}
