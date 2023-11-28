<?php

namespace App\Actions\Query;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserQuery
{
    public function get(Request $request, $limit = 15) {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::callback('company', function ($query, $value) {
                    $query->whereHas('company', function ($q) use ($value) {
                        $q->where('id', $value);
                    });
                })->ignore(null),
                AllowedFilter::callback('display_name', function ($query, $value) {
                    if ($value) {
                        $query->where(function ($q) use ($value) {
                            if (str_contains($value, ' ')) {
                                $values = explode(' ', $value);
                                $q->where('first_name', 'LIKE', '%' . $values[0] . '%')
                                    ->where('last_name', 'LIKE', '%' . $values[1] . '%');
                            } else {
                                $q->where('first_name', 'LIKE', '%' . $value . '%')
                                    ->orWhere('last_name', 'LIKE', '%' . $value . '%')
                                    ->orWhere('email', 'LIKE', "%$value%");
                            }
                        });
                    }
                })->ignore(null),
                AllowedFilter::callback('age', function ($query, $value) {
                    $query->where('birthyear', '<=', (date('Y') - $value[0]))
                        ->where('birthyear', '>=', (date('Y') - $value[1]));
                })->ignore(null),
                AllowedFilter::callback('power', function ($query, $value) {
                    $query->where('power_club', '>=', $value[0])->where('power_club', '<=', $value[1]);
                })->ignore(null),
                AllowedFilter::callback('rank', function ($query, $value) use ($request) {
                    if (!$request->has('filter.club')) {
                        $query->where('rank_global', '<>', 0);
                    }
                    $query->orderBy('rating', 'desc');
                })->ignore(null),
                AllowedFilter::callback('club', function ($query, $club) use ($request) {
                    $query->whereHas('clubs', function ($query) use ($club, $request) {
                        $query->where('club_id', $club);
                        if ($request->has('filter.rank')) {
                            $query->where('rank', '<>', 0);
                        }
                    });
                })->ignore(null),
                AllowedFilter::callback('is_doubles', function ($query, $value) {
                    if ($value) {
                        $query->whereNotNull('is_doubles');
                    } else {
                        $query->whereNull('is_doubles');
                    }
                }),
                AllowedFilter::callback('is_admin', function ($query, $value) use ($request) {
                    if ($value) {
                        $query->whereHas('administered', function ($q) use ($request) {
                            $q->where('clubs.id', $request->get('club')->id);
                        });
                    }
                }),
                AllowedFilter::callback('is_cashier', function ($query, $value) use ($request) {
                    if ($value) {
                        $query->whereHas('clubs', function ($q) use ($request) {
                            $q->where('club_user.club_id', $request->get('club')->id)->where('club_user.cashier', true);
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
                        $query->whereHas('memberships', function ($q) use ($value) {
                            $q->where('membership_id', $value)->whereNull('user_membership.deleted_at');
                        });
                    }
                })
            ])
            ->allowedIncludes(['trainer', 'memberships', 'clubs', 'wallets', 'parents', 'children', 'subscriptions', 'wallet'])
            ->defaultSort('-rating')
            ->allowedSorts(['rating', 'id', AllowedSort::field('name', 'first_name'), 'last_name']);
        if ($limit === -1) {
            $users = $users->get();
        } else {
            $users = $users->paginate($request->input('limit', $limit))
                ->appends($request->query());
        }

        return $users;
    }
}
