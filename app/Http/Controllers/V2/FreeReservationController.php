<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourtCollection;
use App\Http\Resources\ReservationCollection;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class FreeReservationController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api')->except('index');
    }

    public function index(Request $request) {
        $date = request()->input('date');
        if (! $date) {
            $date = date('Y-m-d');
        }
        $from = intval(request()->input('from' ));
        $from = Carbon::createFromFormat('H', $from)->format('H:i:s');
        $to = intval(request()->input('to'));
        $to = Carbon::createFromFormat('H', $to)->format('H:i:s');

        $courts = \Spatie\QueryBuilder\QueryBuilder::for(Court::class)
            ->allowedFilters([
                'lights', 'type', 'is_active', 'heating', 'airconditioner', 'wifi', 'invalid',
                'show_on_tenisplus',
                AllowedFilter::exact('surface', 'surface_id'),
                AllowedFilter::exact('club', 'club_id'),
                AllowedFilter::callback('sport', function ($query, $value) {
                    $query->whereHas('sports', function ($query) use ($value) {
                        $query->where('sports.id', $value);
                    });
                }),
            ])
            ->allowedIncludes(['club', 'sports'])
            ->allowedSorts(['order', AllowedSort::field('club', 'club_id'),
                AllowedSort::callback('distance', function ($query, $direction) use ($request) {
                    $direction = $direction ? 'DESC' : 'ASC';
                    $query->join('clubs', 'courts.club_id', '=', 'clubs.id')->orderByRaw("ST_Distance_Sphere(point(clubs.longitude, clubs.latitude), point(?, ?)) $direction", [
                        $request->input('longitude'),
                        $request->input('latitude'),
                    ]);
                }),
            ])->defaultSort('order')
            ->whereDoesntHave('reservations', function(Builder $query) use ($date, $from, $to) {
                $from = $date . ' ' . $from;
                $to = $date . ' ' . $to;
                $query->where('status', '!=', 'pending');
                $query->whereDate('from', $date)
                    ->whereRaw("(`from` BETWEEN '$from' AND '$to' OR `to` BETWEEN '$from' AND '$to' OR '$from' BETWEEN `from` AND `to` OR '$to' BETWEEN `from` AND `to`)");
            })
            ->with([
                'reservations' => function ($query) use ($date) {
                    $query->whereDate('from', $date);
                },
            ], 'surface')
            ->select('courts.*')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return CourtCollection::make($courts);
    }
}
