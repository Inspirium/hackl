<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeagueGroupResource extends JsonResource
{
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order' => $this->order,
            'move_up' => $this->move_up,
            'move_down' => $this->move_down,
            'name' => $this->name,
            'players_in_group' => $this->players_in_group,
            'players' => TeamResource::collection($this->whenLoaded('players')),
            'games' => GameCollection::make($this->whenLoaded('games')),
            'stats' => $this->stats(),
            'points' => $this->points,
            'points_loser' => $this->points_loser,
            'points_match' => $this->points_match,
            'thread' => $this->whenLoaded('thread')
        ];
    }
}
