<?php

namespace App\Http\Resources;

use App\Models\Invoice;
use App\Models\Team;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/**
 * @mixin Team
 */
class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /*if ($this->number_of_players === 1) {
            $this->load('primary_contact');
            if ($this->primary_contact) {
                $out = UserResource::make($this->primary_contact);
                $out['type'] = 'team';
                $out['id'] = $this->id;
                $out['players'] = $this->players;
                return $out;
            }
        }*/
        return [
            'id' => $this->id,
            'type' => 'team',
            'display_name' => $this->display_name,
            'first_name' => $this->primary_contact?->first_name,
            'last_name' => $this->primary_contact?->last_name,
            'email' => $this->email??$this->primary_contact?->email,
            'image' => url($this->image??$this->primary_contact?->image),
            'rating' => $this->rating,
            'rating_club' => $this->rating_club,
            'power_club' => $this->power_club,
            'power_global' => $this->power,
            'rank_club' => $this->rank_club,
            'rank_global' => $this->rank,
            'range' => $this->primary_contact?->range,
            'link' => $this->link,
            'power' => $this->power,
            'rounded_power' => $this->rounded_power,
            'club_member' => $this->primary_contact ? $this->primary_contact->club_member : [],
            'score' => $this->score,
            'score_club' => $this->score_club,
            'players' => $this->whenLoaded('players'),
            'pivot' => $this->pivot,
            'seed' => $this->when(request()->has('seed'), function () use ($request) {
                $v = DB::table('tournament_player', 'tp')
                    ->leftJoin('tournament_rounds', 'tp.tournament_id', '=', 'tournament_rounds.tournament_id')
                    ->where('tp.player_id', $this->id)
                    ->where('tournament_rounds.id', $request->route()->parameter('tournament_round'))
                    ->first(['seed']);
                return $v->seed ?? null;
            }),
            'stats' => $this->when($request->has('stats'), function () {
                return $this->getStats();
            }),
            'winslosses' => $this->when($request->has('wins'), function () {
                $this->load('results.players');

                return $this->getWinsLosses();
            }),
            'tournament_total_score' => (int)$this->tournament_total_score,
            'tournament_scores' => $this->whenLoaded('tournament_scores'),
            'reservations_count' => (int)$this->reservations_count,
            'active_reservations_count' => (int)$this->active_reservations_count,
            'inactive_reservations_count' => (int)$this->inactive_reservations_count,
            'membership' => $this->primary_contact?->getMembership(),
            'active_subscription' => $this->active_subscription,
            'previous_invoices' => $this->when($request->has('include_invoices'), function() use ($request) {
                /*$id = $request->
                return $id;*/
                $invoices = Invoice::where('user_id', $this->primary_contact_id)
                    ->whereHas('items', function($query) {
                        $query->where('invoiceable_type', 'App\Models\UserSubscription');
                        //->where('invoiceable_id', $id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();
                    $out = [];
                    foreach ($invoices as $invoice) {
                        $out[] = [
                            'id' => $invoice->uuid,
                            'invoice_number' => $invoice->invoice_number,
                            'payment_status' => $invoice->payment_status
                        ];
                    }
                    return $out;
            })
        ];
    }
}
