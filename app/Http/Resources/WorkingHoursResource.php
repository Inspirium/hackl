<?php

namespace App\Http\Resources;

use App\Models\WorkingHours;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class WorkingHoursResource
 *
 * @mixin WorkingHours
 */
class WorkingHoursResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
