<?php

namespace App\Http\Controllers\V2\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserCollection;
use App\Models\Reservation;
use App\Models\ReservationParticipant;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MongoDB\Driver\Query;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StatisticController extends Controller
{
    public function index(Request $request) {
        $reservations = QueryBuilder::for(ReservationParticipant::class)
            ->allowedFilters([
                AllowedFilter::callback('created_between', function($query, $value) {
                    $query->whereHas('reservation', function($query) use ($value) {
                        $query->whereBetween('created_at',  [ Carbon::parse($value[0]), Carbon::parse($value[1]??'now') ]);
                    });
                }),
                AllowedFilter::callback('active_between', function($query, $value) {
                    $query->whereHas('reservation', function($query) use ($value) {
                        $query->whereBetween('from',  [ Carbon::parse($value[0]), Carbon::parse($value[1]??'now') ]);
                    });

                }),
                AllowedFilter::callback('club', function($query, $value) {
                    $query->whereHas('reservation', function($query) use ($value) {
                        $query->whereHas('court', function($query) use ($value) {
                            $query->where('club_id', $value);
                        });
                    });
                })->default($request->get('club')->id),
                AllowedFilter::callback('court', function($query, $value) {
                    $query->whereHas('reservation', function($query) use ($value) {
                        $query->where('court_id', $value);
                    });
                }),
            ])
            ->leftJoin('reservations', 'reservations.id', '=', 'users_reservations.reservation_id')
            ->select('users_reservations.*', DB::raw('COUNT(users_reservations.reservation_id) as reservations_count'),
                DB::raw('SUM(if(reservations.deleted_at IS NULL, 1, 0 )) as active_reservations_count'),
                DB::raw('SUM(if(reservations.deleted_at IS NOT NULL, 1, 0)) as inactive_reservations_count')
            )
            ->allowedSorts([
                'reservations_count', 'active_reservations_count', 'inactive_reservations_count'
            ])->defaultSort('-reservations_count')
            ->where('users_reservations.status', 'player')
            ->groupBy('users_reservations.user_id')
          /*  ->toSql();
        return response()->json($reservations);
        */
            ->paginate($request->input('limit'))
            ->appends($request->query());

        $buyers = [];
        foreach ($reservations as $order) {
            $buyer = Team::query()->withTrashed()->find($order->user_id);
            if ($buyer) {
                $buyer->reservations_count = $order->reservations_count;
                $buyer->active_reservations_count = $order->active_reservations_count;
                $buyer->inactive_reservations_count = $order->inactive_reservations_count;
                $buyers[] = $buyer;
            }
        }
        return TeamResource::collection($buyers);

        return ReservationResource::collection($reservations);
    }
}
