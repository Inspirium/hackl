<?php

namespace App\Http\Resources;

use App\Models\SchoolGroup;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SchoolGroupResource
 *
 * @mixin SchoolGroup
 */
class SchoolGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $out = parent::toArray($request);
        $out['players'] = TeamResource::collection($this->players);
        return $out;
    }
}
