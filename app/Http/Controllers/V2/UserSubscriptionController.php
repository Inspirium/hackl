<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Subscription $subscription)
    {
        $users = collect($request->input('users'))->pluck('id');
        $type = $request->input('type');
        switch($type) {
            case 'schoolGroup':
                $type = 'App\Models\SchoolGroup';
                break;
                case 'membership':
                $type = 'App\Models\Membership';
                break;
        }
        $typeId = $request->input('type_id');
        $price = $request->input('price', null);
        $is_paused = $request->input('is_paused', false);
        $all = $request->input('all', false);
        if ($all) {
            UserSubscription::query()->where('subscribable_type', $type)->where('subscribable_id', $typeId)->delete();
        } else {
            $subscription->users()->detach($users);
        }
        $subscription->users()->attach($users, ['subscribable_type' => $type, 'subscribable_id' => $typeId, 'price' => $price, 'is_paused' => $is_paused]);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSubscription $userSubscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserSubscription $userSubscription)
    {
        $validated = $request->validate([
            'price' => 'sometimes|numeric',
            'is_paused' => 'sometimes|boolean',
        ]);

        $userSubscription->update($validated);

        return JsonResource::make($userSubscription);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserSubscription $userSubscription)
    {
        $userSubscription->delete();
        return response()->json(['message' => 'Subscription deleted.']);
    }
}
