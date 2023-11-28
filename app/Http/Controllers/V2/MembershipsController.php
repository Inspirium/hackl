<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\MembershipsCollection;
use App\Http\Resources\MembershipsResource;
use App\Models\Club;
use App\Models\Membership;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class MembershipsController extends Controller
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
    public function index(Request $request, Club $club)
    {
        $membership = QueryBuilder::for(Membership::class)
            ->where('club_id', $club->id)
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return MembershipsCollection::make($membership);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Club $club)
    {
        if (!\Auth::user()->is_admin->contains($club->id)) {
            return response()->json(['message' => 'You are not authorized to create membership for this club'], 403);
        }

        $validated = $request->validate([
            'name' => 'required',
            'max_reservation_span' => 'required|integer',
            'is_reservation_cancelable' => 'required|boolean',
            'reservation_cancelable' => 'sometimes|nullable|integer',
            'has_discount' => 'required|boolean',
            'discount_type' => 'sometimes|nullable|integer',
            'discount_amount' => 'sometimes|nullable|numeric',
            'price' => 'required|numeric',
            'public' => 'sometimes|nullable|boolean',
            'description' => 'sometimes|nullable',
            'basic' => 'sometimes|nullable|boolean',
            'max_reservation_per_period' => 'sometimes|nullable|boolean',
            'max_reservation_per_period_days' => 'sometimes|nullable|integer',
            'reservation_prepayment' => 'sometimes|nullable|boolean',
            'subscription' => 'sometimes|nullable|array',
            'business_unit.id' => 'sometimes|nullable|integer',
            'tax_class.id' => 'sometimes|nullable|integer',
        ]);

        $validated['club_id'] = $club->id;

        if (isset($validated['basic']) && $validated['basic']) {
            //remove other basic membership
            $club->memberships()->where('basic', 1)->update(['basic' => 0]);
        } else {
            if (! $club->memberships()->where('basic', 1)->count()) {
                $validated['basic'] = 1;
            }
        }
        if (isset($validated['business_unit']['id'])) {
            $validated['business_unit_id'] = $validated['business_unit']['id'];
            unset($validated['business_unit']);
        }
        if (isset($validated['tax_class']['id'])) {
            $validated['tax_class_id'] = $validated['tax_class']['id'];
            unset($validated['tax_class']);
        }
        $membership = Membership::create($validated);

        return MembershipsResource::make($membership);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club, Membership $membership)
    {
        return MembershipsResource::make($membership);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Club $club, Membership $membership)
    {
        if (!\Auth::user()->is_admin->contains($club->id)) {
            return response()->json(['message' => 'You are not authorized to create membership for this club'], 403);
        }
        $validated = $request->validate([
            'name' => 'required',
            'max_reservation_span' => 'required|integer',
            'is_reservation_cancelable' => 'required|boolean',
            'reservation_cancelable' => 'sometimes|nullable|integer',
            'has_discount' => 'required|boolean',
            'discount_type' => 'sometimes|nullable|integer',
            'discount_amount' => 'sometimes|nullable|numeric',
            'price' => 'required|numeric',
            'public' => 'sometimes|boolean',
            'description' => 'sometimes|nullable',
            'basic' => 'sometimes|nullable|boolean',
            'max_reservation_per_period' => 'sometimes|nullable|boolean',
            'max_reservation_per_period_days' => 'sometimes|nullable|integer',
            'reservation_prepayment' => 'sometimes|nullable|boolean',
            'subscription' => 'sometimes|nullable|array',
            'business_unit.id' => 'sometimes|nullable|integer',
            'tax_class.id' => 'sometimes|nullable|integer',
        ]);

        if (isset($validated['basic']) && $validated['basic']) {
            $club->memberships()->where('basic', 1)->whereKeyNot($membership->id)->update(['basic' => 0]);
        }
        if (isset($validated['business_unit']['id'])) {
            $validated['business_unit_id'] = $validated['business_unit']['id'];
            unset($validated['business_unit']);
        }
        if (isset($validated['tax_class']['id'])) {
            $validated['tax_class_id'] = $validated['tax_class']['id'];
            unset($validated['tax_class']);
        }
        $membership->update($validated);

        return MembershipsResource::make($membership);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Membership  $membership
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $club, Membership $membership)
    {
        if (!\Auth::user()->is_admin->contains($club->id)) {
            return response()->json(['message' => 'You are not authorized to create membership for this club'], 403);
        }
        if ($membership->basic) {
            $ms = Membership::where('club_id', $club->id)->whereNot('id', $membership->id)->first();
            if ($ms) {
                $ms->update(['basic' => 1]);
            } else {
                return response('Not allowed');
            }
        }
        $membership->users()->detach();
        $membership->delete();

        return response()->noContent();
    }
}
