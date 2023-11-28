<?php

namespace App\Models\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class RankSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $query->join('club_team', 'teams.id', '=', 'club_team.team_id')
            ->where('club_team.club_id', request()->input('filter.club'))
            ->orderBy('club_team.rank', 'desc');
    }
}
