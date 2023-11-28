<?php

namespace App\Http\Resources;

use App\Models\Club;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ClubResource
 *
 * @mixin Club
 */
class ClubResource extends JsonResource
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
