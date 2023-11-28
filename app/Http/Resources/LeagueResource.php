<?php

namespace App\Http\Resources;

use App\Models\League;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/**
 * @mixin League
 */
class LeagueResource extends JsonResource
{
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
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'number_of_groups' => $this->number_of_groups,
            'players_in_groups' => $this->players_in_groups,
            'rounds_of_play' => $this->rounds_of_play,
            'points' => $this->points,
            'move_up' => $this->move_up,
            'move_down' => $this->move_down,
            'playing_sets' => $this->playing_sets,
            'game_in_set' => $this->game_in_set,
            'last_set' => $this->last_set,
            'finish_date' => $this->finish_date?->toDateTimeString(),
            'start_date' => $this->start_date?->toDateTimeString(),
            'finish_onboarding' => $this->finish_onboarding?->toDateTimeString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'status' => $this->status,
            'points_loser' => $this->points_loser,
            'points_match' => $this->points_match,
            'points_set_winner' => $this->points_set_winner,
            'boarding_type' => $this->boarding_type,
            'classification' => $this->classification,
            'players' => TeamResource::collection($this->whenLoaded('players')),
            'admins' => TeamResource::collection($this->whenLoaded('admins')),
            'groups' => LeagueGroupCollection::make($this->whenLoaded('groups')),
            'documents' => DocumentCollection::make($this->whenLoaded('documents')),
            'parent' => LeagueResource::make($this->whenLoaded('parent')),
            'child' => LeagueResource::make($this->whenLoaded('child')),
            'is_doubles' => $this->is_doubles,
            'freeze' => $this->freeze,
            'show_tournament' => $this->show_tournament,
            'club_id' => $this->club_id,
            'groups_custom_points' => $this->groups_custom_points,
            'show_on_tenisplus' => $this->show_on_tenisplus,
            'club' => $this->whenLoaded('club'),
            'thread' => $this->whenLoaded('thread'),
            'show_in_club' => $this->show_in_club,
            'competition_id' => $this->competition_id,
            'competition' => $this->whenLoaded('competition'),
        ];
    }
}
