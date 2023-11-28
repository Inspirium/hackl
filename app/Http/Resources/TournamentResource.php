<?php

namespace App\Http\Resources;

use App\Models\Tournament;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TournamentResource
 *
 * @mixin Tournament
 */
class TournamentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = parent::toArray($request);
        if (isset($return['data'])) {
            $return = array_merge($return, $return['data']);
            unset($return['data']);
        }
        if (isset($return['winner']['image'])) {
            $return['winner']['image'] = url('storage/'.$return['winner']['image']);
        }
        $return['players'] = TeamResource::collection($this->whenLoaded('players'));

        return $return;
    }
}
