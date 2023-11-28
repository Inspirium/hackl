<?php

namespace App\Http\Controllers\V2;

use App\Actions\Exports\PlayersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ExportController extends Controller
{

    public function players(Request $request) {
        $players = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('club', function ($query, $value) {
                    $query->whereHas('clubs', function ($query) use ($value) {
                        $query->where('clubs.id', $value);
                    });
                }),
                AllowedFilter::callback('is_admin', function ($query, $value) use ($request) {
                    if ($value) {
                        $query->whereHas('administered', function ($q) use ($request) {
                            $q->where('clubs.id', $request->get('club')->id);
                        });
                    }
                }),
                AllowedFilter::callback('is_trainer', function ($query, $value) {
                    if ($value) {
                        $query->has('trainer');
                    }
                }),
                AllowedFilter::callback('club_member_status', function ($query, $value) use ($request) {
                    if ($value) {
                        $query->whereHas('clubs', function ($q) use ($request, $value) {
                            $q->where('clubs.id', $request->get('club')->id)->where('club_user.status', $value);
                        });
                    }
                }),
                AllowedFilter::callback('membership', function ($query, $value) {
                    if ($value) {
                        $query->whereHas('memberships', function($q) use ($value) {
                            $q->where('membership_id', $value)->whereNull('user_membership.deleted_at');
                        });
                    }
                })
            ]);

        $export = new PlayersExport($players);

        return $export->export();
    }
}
