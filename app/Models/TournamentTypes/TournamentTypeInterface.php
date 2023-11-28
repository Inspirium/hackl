<?php

namespace App\Models\TournamentTypes;

use Illuminate\Http\Request;

interface TournamentTypeInterface
{
    public function validate(Request $request): array;

    public function prepareData(array $validated): array;

    public function save(array $data);
}
