<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkingHoursCollection;
use App\Http\Resources\WorkingHoursResource;
use App\Models\Court;
use App\Models\WorkingHours;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkingHoursController extends Controller
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
    public function index(Court $court)
    {
        $court->load('working_hours');

        return WorkingHoursCollection::make($court->working_hours);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Court $court)
    {
        if ($request->has('working_hours')) {
            $hours = [];
            foreach ($request->input('working_hours') as $hour) {
                $h = new WorkingHours();
                $h->cron = $hour['cron'];
                $h->working = 1;
                $h->active_from = Carbon::now();
                $h->active_to = Carbon::now()->addYear(10);
                $h->court_id = $court->id;
                $h->price = $hour['price'];
                if (isset($hour['tax_class.id'])) {
                    $h->tax_class_id = $hour['tax_class.id'];
                }
                if (isset($hour['membership']) && isset($hour['membership']['id']) && $hour['membership']['id']) {
                    $h->membership_id = $hour['membership']['id'];
                }
                $h->save();
                if (isset($hour['category'])) {
                    $h->category()->sync($request['category.id']);
                }
                $hours[] = $h;
            }

            return WorkingHoursCollection::make($hours);
        } else {
            $validated = $request->validate([
                'days' => 'required|array',
                'hours' => 'required|array',
                'price' => 'required|numeric',
                'tax_class.id' => 'sometimes|nullable|integer',
            ]);
            $days = $validated['days'];
            sort($days);
            if (count($days) === 7) {
                $days = '*';
            } else {
                $days = implode(',', $days);
            }
            $hour = $validated['hours'];
            sort($hour);
            if (count($hour) === 24) {
                $hour = '*';
            } else {
                //TODO dvosmjensko vrijeme
                $hour = $hour[0].'-'.($hour[count($hour) - 1] + 1);
            }
            $cron = '* '.$hour.' * * '.$days;
            $h = new WorkingHours();
            $h->cron = $cron;
            $h->working = 1;
            $h->active_from = Carbon::now();
            $h->active_to = Carbon::now()->addYear(10);
            $h->court_id = $court->id;
            $h->price = $validated['price'];
            if (isset($validated['tax_class.id'])) {
                $h->tax_class_id = $validated['tax_class.id'];
            }
            if ($request->has('membership.id')) {
                $h->membership_id = $request->input('membership.id', null);
            }
            $h->save();
            if (isset($request['category'])) {
                $h->category()->sync($request['category.id']);
            }
            return WorkingHoursResource::make($h);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkingHours  $hour
     * @return \Illuminate\Http\Response
     */
    public function show(Court $court, WorkingHours $hour)
    {
        return WorkingHoursResource::make($hour);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkingHours  $hour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Court $court, WorkingHours $hour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkingHours  $hour
     * @return \Illuminate\Http\Response
     */
    public function destroy(Court $court, WorkingHours $hour)
    {
        $hour->delete();

        return response()->noContent();
    }
}
