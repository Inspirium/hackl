<?php

namespace App\Http\Resources;

use App\Models\Court;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CourtResource
 *
 * @mixin Court
 */
class CourtResource extends JsonResource
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
        $parsed_reservations = [];
        $working_span = [];
        if ($request->has('date')) {
                $parsed_reservations = $this->getParsedReservations($request->input('date'));
                $working_span = $this->getWorkingHours($request->input('date'));

        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'surface' => $this->surface,
            'reservation_duration' => $this->reservation_duration,
            'reservation_confirmation' => $this->reservation_confirmation,
            'reservation_hole' => $this->reservation_hole,
            'cover' => $this->cover,
            'weather' => $this->weather,
            'lights' => $this->lights,
            'parsed_reservations' => $parsed_reservations,
            'working_span' => $working_span,
            'working_hours' => $this->working_hours,
            'hero_image' => $this->hero_image,
            'club_id' => $this->club_id,
            'club' => $this->whenLoaded('club'),
            'show_on_tenisplus' => $this->show_on_tenisplus,
		    'sports' => $this->whenLoaded('sports', SportResource::collection($this->sports)),
            'member_reservation' => $this->member_reservation,
            'reservation_prepayment' => $this->needs_payment,
            'court_break_from' => $this->court_break_from,
            'court_break_to' => $this->court_break_to,
		'airconditioner' => $this->airconditioner,
            'wifi' => $this->wifi,
            'heating' => $this->heating,
	    'size' => $this->size,
	    'invalid' => $this->invalid,
	    'description' => $this->description
	];
    }
}
