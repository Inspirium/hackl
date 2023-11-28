<?php

namespace App\Http\Resources;

use App\Actions\VSStats;
use App\Models\Reservation;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ReservationResource
 *
 * @mixin Reservation
 */
class ReservationResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
        if (request()->is('club/schedule/*')) {
            $this->preserveKeys = true;
        }
    }

    public $preserveKeys = false;
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
            'court' => CourtResource::make($this->whenLoaded('court')),
            'from' => $this->from,
            'to' => $this->to,
            'price' => $this->price,
            'players' => $this->game_id ? TeamResource::collection($this->game->players) : TeamResource::collection($this->players),

            'name' => $this->name,
            'type' => $this->type,
            'series' => $this->series,
            'schoolGroup' => $this->whenLoaded('school_group'),
            'attendances' => $this->whenLoaded('attendances'),
            'game' => $this->whenLoaded('game', GameResource::make($this->game)),
            'payment_invoice' => $this->payment_invoice,
            'payment_note' => $this->payment_note,
            'payment_date' => $this->payment_date,
            'payment_user' => $this->payment_user,
            'is_paid' => $this->getIsPaidAttribute(),
            'prices' => $this->getPrices(),
            'payments' => $this->whenLoaded('payments'),
            'category' => $this->whenLoaded('category'),
            'school_performances' => $this->whenLoaded('school_performances', $this->school_performances->keyBy('player_id')),
            'vsStats' => $this->when(request()->is('club/schedule/*') && $request->get('club')->id != 26, function() {
                return [
                    'stats' => [],
                    'totals' => [],
                    'results' => [],
                ];
            }),
            'watchers' => UserCollection::make($this->whenLoaded('watchers')),
            'status' => $this->status,
            'applicant' => $this->applicant,
            'email' => $this->email,
            'description' => $this->description,
            'note' => $this->note,
            'created_by' => $this->created_by,
            'canceled_by' => $this->canceled_by,
        ];
        if (!request()->is('club/schedule/*')) {
            $out2 = [
                'created_at' => $this->created_at,
                'deleted_at' => $this->deleted_at,
                'created_by' => $this->created_by,
                'canceled_by' => $this->canceled_by,
                'needs_payment' => $this->needs_payment,
            ];
            $out = array_merge($out, $out2);
        }

        return $out;
    }
}
