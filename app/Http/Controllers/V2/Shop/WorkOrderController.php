<?php

namespace App\Http\Controllers\V2\Shop;

use App\Http\Controllers\Controller;
use App\Models\WorkOrder;
use App\Notifications\Shop\NewOrderCompleted;
use App\Notifications\Shop\NewWorkOrderCompleted;
use App\Notifications\Shop\NewWorkOrderCreated;
use App\Notifications\SpannungComplete;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class WorkOrderController extends Controller
{

    private $route = 'order';

    private $model = 'App\Models\Shop\Order';

    public function __construct(Request $request)
    {
        $this->middleware('auth:api')->except('show');

        /* if ($request->route()) {
            $name = explode('.', $request->route()->getName())[0];
            switch ($name) {
                case 'order':
                    $this->route = 'order';
                    $this->model = 'App\Models\Shop\Order';
                    break;
            }
        } */
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = QueryBuilder::for(WorkOrder::class)
            ->allowedFilters(['id', 'status', 'created_at',
                AllowedFilter::exact('assignee', 'assignee_id'),
                AllowedFilter::exact('assigner', 'assigner_id'),
                AllowedFilter::exact('order', 'orderable_id'),
                AllowedFilter::callback('created_between', function($query, $value) {
                    $query->whereBetween('created_at',  [ Carbon::parse($value[0]), Carbon::parse($value[1]??'now') ]);
                }),
                AllowedFilter::exact('club', 'club_id')->default($request->get('club')->id??0),
                ])
            ->allowedIncludes(['assignee', 'assigner',
                AllowedInclude::relationship('order', 'orderable')])
            ->allowedSorts('created_at', 'updated_at')
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return JsonResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $order = null)
    {
        $validated = $request->validate([
            'assignee.id' => 'required|exists:users,id',
            'assigner.id' => 'sometimes|exists:users,id',
            'status' => 'sometimes|in:pending,completed,cancelled',
            'data' => 'sometimes|nullable|array',
            'note' => 'sometimes',
        ]);

        $validated['assignee_id'] = $validated['assignee']['id'];
        unset($validated['assignee']);

        if (isset($validated['assigner'])) {
            $validated['assigner_id'] = $validated['assigner']['id'];
            unset($validated['assigner']);
        } else {
            $validated['assigner_id'] = \Auth::id();
        }
        if ($order) {
            $validated['orderable_id'] = $order;
            $validated['orderable_type'] = $this->model;
        }
        $validated['club_id'] = $request->get('club')->id;
        $workOrder = WorkOrder::create($validated);
        if ($validated['status'] === 'completed') {
            $workOrder->assigner->notify((new NewWorkOrderCompleted($workOrder, \Auth::user()))->locale($workOrder->assigner->lang));
            if ($workOrder->orderable_id) {
                $workOrder->orderable->buyer->notify((new NewOrderCompleted($workOrder->orderable, \Auth::user()))->locale($workOrder->orderable->buyer->lang));
            }
        } else {
            $workOrder->assignee->notify((new NewWorkOrderCreated($workOrder, \Auth::user()))->locale($workOrder->assignee->lang));
        }

        return JsonResource::make($workOrder);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkOrder  $workOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id, $id2 = null)
    {
        $wo = QueryBuilder::for(WorkOrder::class)
            ->allowedIncludes(['assignee', 'assigner',
                AllowedInclude::relationship('order', 'orderable')])
            ->find($id2??$id);
        return JsonResource::make($wo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkOrder  $workOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $id2 = null)
    {
        if ($id2) {
            $work_order = WorkOrder::find($id2);
        } else{
            $work_order = WorkOrder::find($id);
        }
        $validated = $request->validate([
            'assignee.id' => 'sometimes|exists:users,id',
            'assigner.id' => 'sometimes|exists:users,id',
            'status' => 'sometimes|in:pending,completed,cancelled',
            'data' => 'sometimes|nullable|array',
            'note' => 'sometimes',
        ]);

        if (isset($validated['assignee'])) {
            $validated['assignee_id'] = $validated['assignee']['id'];
            unset($validated['assignee']);
        }
        if (isset($validated['assigner'])) {
            $validated['assigner_id'] = $validated['assigner']['id'];
            unset($validated['assigner']);
        }
        $status = $work_order->status;
        $work_order->update($validated);
        if ($status != $work_order->status && $work_order->status === 'completed') {
            if ($work_order->orderable_id) {
                $work_order->orderable->buyer->notify((new NewOrderCompleted($work_order->orderable, \Auth::user()))->locale($work_order->orderable->buyer->lang));
            }
            $work_order->assigner->notify((new NewWorkOrderCompleted($work_order, \Auth::user()))->locale($work_order->assigner->lang));
        }
        return JsonResource::make($work_order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkOrder  $workOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id2 = null)
    {
        if ($id2) {
            $workOrder = WorkOrder::find($id2);
        } else{
            $workOrder = WorkOrder::find($id);
        }
        $workOrder->delete();
        return response()->json(null, 204);
    }
}
