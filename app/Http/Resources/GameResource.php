<?php

namespace App\Http\Resources;

use App\Models\Game;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Game
 */
class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $out = [
            'id' => $this->id,
            'type_id' => $this->type_id,
            'played_at' => $this->played_at,
            'created_at' => $this->created_at,
            'is_surrendered' => $this->is_surrendered,
            'players' => TeamResource::collection($this->whenLoaded('players')),
            'result' => ResultResource::make($this->result),
            'live' => $this->live,
            'reservation' => $this->whenLoaded('reservation', $this->reservation),
        ];
        return $out;
    }
}
