<?php

namespace App\Http\Resources;

use App\Models\OtherExpense;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OtherExpenseResource
 *
 * @mixin OtherExpense
 */
class OtherExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        $array['class'] = 'App\Models\OtherExpense';

        return $array;
    }
}
