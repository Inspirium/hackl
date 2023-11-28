<?php

namespace App\Http\Resources;

use App\Models\Trainer;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TrainerResource
 *
 * @mixin Trainer
 */
class TrainerResource extends JsonResource
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
