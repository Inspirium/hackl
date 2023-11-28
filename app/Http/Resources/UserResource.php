<?php

namespace App\Http\Resources;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/**
 * Class UserResource
 *
 * @mixin User
 */
class UserResource extends JsonResource
{
    private $membership;

    private $token;

    public function __construct($resource, $membership = null, $token = null)
    {
        parent::__construct($resource);
        if ($membership) {
            $this->membership = $membership;
        }
        if ($this->relationLoaded('memberships')) {
            $this->membership = $this->memberships;
        }
        if ($token) {
            $this->token = $token;
        }
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'player',
            'id' => $this->id,
            'display_name' => $this->display_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'image' => url($this->image),
            'gender' => $this->gender,
            'phone' => $this->phone,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'city' => $this->city,
            'county' => $this->county,
            'country' => $this->country,
            'birthyear' => $this->birthyear,
            'birth_date' => $this->birth_date,
            'rating' => $this->rating,
            'power_club' => $this->power_club,
            'power_global' => $this->power_global,
            'rank_club' => $this->rank_club,
            'rank_global' => $this->rank_global,
            'range' => $this->range,
            'link' => $this->link,
            'power' => $this->power,
            'rounded_power' => $this->rounded_power,
            'club_member' => $this->club_member,
            'admin' => $this->is_admin,
            'score' => $this->score,
            'is_trainer' => $this->is_trainer,
            'is_admin' => collect($this->is_admin)->contains($request->get('club') ? $request->get('club')->id : $this->primary_club_id),
            'trainer' => $this->whenLoaded('trainer', function () {
                return $this->trainer;
            }),
            'winslosses' => $this->when($request->has('wins'), function () {
                $this->load('results.players');

                return $this->getWinsLosses();
            }),
            'stats' => $this->when($request->has('stats'), function () {
                return $this->getStats();
            }),
            'membership' => $this->when($this->membership, $this->membership),
            //'memberships' => $this->when($this->relationLoaded('memberships'), $this->memberships),
            'pivot' => $this->pivot,
            'primary_club_id' => $this->primary_club_id,
            'seed' => $this->when($request->route()->parameterNames && $request->route()->parameterNames[0] === 'tournament_round', function () use ($request) {
                //return $request->route()->parameter('tournament_round');
                $v = DB::table('tournament_player', 'tp')
                    ->leftJoin('tournament_rounds', 'tp.tournament_id', '=', 'tournament_rounds.tournament_id')
                    ->where('tp.player_id', $this->id)
                    ->where('tournament_rounds.id', $request->route()->parameter('tournament_round'))
                    ->first(['seed']);

                return $v->seed ?? null;
            }),
            'is_doubles' => $this->is_doubles,
            'wallets' => $this->whenLoaded('wallets', function () {
                return WalletResource::collection($this->wallets);
            }),
            'wallet' => $this->whenLoaded('wallet', function () {
                return WalletResource::make($this->wallet);
            }),
            // 'clubs' => $request->has('include.clubs')?ClubResource::collection($this->whenLoaded('clubs')):[],
            'token' => $this->when($this->token, $this->token),
            'team' => TeamResource::make(Team::query()->where('number_of_players', 1)->where('primary_contact_id', $this->id)->first()),
            'teams' => $this->whenLoaded('teams', function () {
                return TeamResource::collection($this->teams);
            }),
            'orders_total' => $this->orders_total,
            'orders_count' => $this->orders_count,
            'work_orders_count' => $this->work_orders_count,
            'parents' => $this->whenLoaded('parents', function () {
                return UserResource::collection($this->parents);
            }),
            'children' => $this->whenLoaded('children', function () {
                return UserResource::collection($this->children);
            }),
            'lang' => $this->lang,
            'hidden_fields' => $this->hidden_fields,
            'subscriptions' => $this->whenLoaded('subscriptions', function () {
                return JsonResource::collection($this->subscriptions);
            }),
            'hidden_notifications' => $this->hidden_notifications,
        ];
    }
}
