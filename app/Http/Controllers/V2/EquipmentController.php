<?php

namespace App\Http\Controllers\V2;

use App\Actions\SaveImageAction;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EquipmentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipment = QueryBuilder::for(Equipment::class)
            ->allowedFilters([
                AllowedFilter::exact('player', 'player_id')->default(\Auth::id())
            ])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($equipment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'name' => 'required',
            'player.id' => 'sometimes|exists:users,id',
            'data' => 'sometimes',
            'description' => 'sometimes',
            'image' => 'sometimes|nullable|image64:jpeg,jpg,png',
        ]);

        if(isset($validated['player'])) {
            $validated['player_id'] = $validated['player']['id'];
            unset($validated['player']);
        } else {
            $validated['player_id'] = \Auth::id();
        }

        if(isset($validated['image'])) {
            $validated['image'] = $saveImageAction->execute($validated['image'], 'equipment');
        }
        $equipment = Equipment::create($validated);
        return JsonResource::make($equipment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        return JsonResource::make($equipment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Equipment $equipment, SaveImageAction $saveImageAction)
    {
        $validated = $request->validate([
            'name' => 'sometimes',
            'player.id' => 'sometimes|exists:users,id',
            'data' => 'sometimes',
            'description' => 'sometimes',
            'new_image' => 'sometimes|image64:jpeg,jpg,png',
        ]);

        if(isset($validated['player'])) {
            $validated['player_id'] = $validated['player']['id'];
            unset($validated['player']);
        } else {
            $validated['player_id'] = \Auth::id();
        }
        if(isset($validated['new_image'])) {
            $validated['image'] = $saveImageAction->execute($validated['image'], 'equipment');
            unset($validated['new_image']);
        }
        $equipment->update($validated);

        return JsonResource::make($equipment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return response()->noContent();
    }
}
