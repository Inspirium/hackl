<?php

namespace App\Http\Controllers\V2\SchoolGroup;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolGroup;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceController extends Controller
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
        $attendances = QueryBuilder::for(Attendance::class)
            ->allowedFilters([
                AllowedFilter::exact('school_group', 'school_group_id'),
                AllowedFilter::exact('user', 'user_id'),
                AllowedFilter::exact('reservation', 'reservation_id'),
            ])
            ->allowedIncludes(['school_group', 'user', 'reservation'])
            ->get();

        return response()->json($attendances);
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
            'school_group.id' => 'required|integer',
            'reservation.id' => 'required|integer',
            'user.id' => 'required|integer',
        ]);
        $schoolGroup = SchoolGroup::with(['trainers'])->find($validated['school_group']['id']);

        if ($schoolGroup->trainers->contains(\Auth::user())) {
            $a = Attendance::create([
                'school_group_id' => $schoolGroup->id,
                'reservation_id' => $validated['reservation']['id'],
                'user_id' => $validated['user']['id'],
            ]);
            if ($a) {
                return response()->json(['message' => 'success']);
            }
        }

        return response()->noContent(401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->noContent();
    }
}
