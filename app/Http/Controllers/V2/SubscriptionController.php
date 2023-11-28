<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = QueryBuilder::for(Subscription::class)
            ->allowedFilters([
                AllowedFilter::exact('club', 'club_id'),
            ])
            ->allowedIncludes([
                'club',
                'tax_class',
                'business_unit',
            ])
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return JsonResource::collection($subscriptions);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'sometimes',
            'status' => 'sometimes',
            'interval' => 'sometimes',
            'description' => 'sometimes',
            'bank_account' => 'sometimes',
            'bank_account_owner' => 'sometimes',
            'bank_account_owner_address' => 'sometimes',
            'bank_account_owner_address2' => 'sometimes',
            'sending_bills' => 'sometimes',
            'warnings' => 'sometimes',
            'bank_model' => 'sometimes',
            'bank_call_number_format' => 'sometimes',
            'tax_class.id' => 'sometimes|exists:tax_classes,id',
            'business_unit.id' => 'sometimes|exists:business_units,id',
        ]);
        $validated['currency'] = 'EUR';

        if (isset($validated['tax_class'])) {
            $validated['tax_class_id'] = $validated['tax_class']['id'];
            unset($validated['tax_class']);
        }
        if (isset($validated['business_unit'])) {
            $validated['business_unit_id'] = $validated['business_unit']['id'];
            unset($validated['business_unit']);
        }

        $subscription = Subscription::create($validated);

        $subscription->club()->associate($request->get('club'));
        $subscription->save();

        return JsonResource::make($subscription);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show($subscription)
    {
        $subscription = QueryBuilder::for(Subscription::class)
            ->allowedIncludes([
                'club',
                'tax_class',
                'business_unit',
            ])
            ->findOrFail($subscription);
        return JsonResource::make($subscription);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'sometimes',
            'status' => 'sometimes',
            'interval' => 'sometimes',
            'description' => 'sometimes',
            'bank_account' => 'sometimes',
            'bank_account_owner' => 'sometimes',
            'bank_account_owner_address' => 'sometimes',
            'bank_account_owner_address2' => 'sometimes',
            'sending_bills' => 'sometimes',
            'warnings' => 'sometimes',
            'bank_model' => 'sometimes',
            'bank_call_number_format' => 'sometimes',
            'tax_class.id' => 'sometimes|exists:tax_classes,id',
            'business_unit.id' => 'sometimes|exists:business_units,id',
        ]);

        if (isset($validated['tax_class'])) {
            $validated['tax_class_id'] = $validated['tax_class']['id'];
            unset($validated['tax_class']);
        }
        if (isset($validated['business_unit'])) {
            $validated['business_unit_id'] = $validated['business_unit']['id'];
            unset($validated['business_unit']);
        }

        $subscription->update($validated);

        return JsonResource::make($subscription);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response()->json(['message' => 'Subscription deleted']);
    }
}
