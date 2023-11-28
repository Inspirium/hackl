<?php

namespace App\Models\Includes;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Includes\IncludeInterface;

class PlayerInclude implements IncludeInterface
{
    private $column;

    public function __construct($column)
    {
        $this->column = $column;
    }

    public function __invoke(Builder $query, string $include)
    {
        $query->with(
            [
                'players' => function ($q) {
                    $q->where($this->column, 1);
                },
            ]
        );
    }
}
