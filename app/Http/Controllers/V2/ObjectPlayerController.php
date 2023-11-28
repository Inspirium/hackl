<?php

namespace App\Http\Controllers\V2;

use App\Actions\CreateLeagueGames;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Game;
use App\Models\League;
use App\Models\Team;
use App\Notifications\NewPlayerInLeague;
use App\Notifications\NewPlayerInTournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ObjectPlayerController extends Controller
{
    private $route = 'league';

    private $model = 'App\Models\League';

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
        if ($request->route()) {
            $name = explode('.', $request->route()->getName())[0];
            switch ($name) {
                case 'league':
                    $this->route = 'league';
                    $this->model = 'App\Models\League';
                    break;
                case 'league_group':
                    $this->route = 'league_group';
                    $this->model = 'App\Models\League\Group';
                    break;
                case 'tournament':
                    $this->route = 'tournament';
                    $this->model = 'App\Models\Tournament';
                    break;
                case 'game':
                    $this->route = 'game';
                    $this->model = 'App\Models\Game';
                    break;
                case 'team':
                    $this->route = 'team';
                    $this->model = 'App\Models\Team';
                    break;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $players = QueryBuilder::for($this->model::where('id', $id)->first()->players())
            ->allowedFilters([
                AllowedFilter::callback('admin', function ($query, $value) {
                    $query->where('tournament_player.admin', 1);
                }),
                AllowedFilter::callback('player', function ($query) {
                    $query->where('tournament_player.player', 1);
                }),
                AllowedFilter::callback('seed', function ($query) {
                    $query->where('tournament_player.seed', '>', 0);
                }),
                AllowedFilter::callback('freeze', function ($query) {
                    $query->where('tournament_player.freeze', 1);
                }),
            ])
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return TeamResource::collection($players);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $object = $this->model::findOrFail($id);
        $players = $request->input('players');
        if ($players) {
            foreach ($players as $player) {
                if (isset($player['id'])) {
                    if (!isset($player['type'])) {
                        $type = 'player';
                    } else {
                        $type = 'team';
                    }
                    if ($type == 'player' || $player['type'] == 'player'){
                        $team = Team::query()->where('number_of_players', 1)->where('primary_contact_id', $player['id'])->first();
                    } else {
                        $team = Team::find($player['id']);
                    }
                    $pivot = ['player' => true];
                    if (array_key_exists('seed', $player) && $object->players()->hasPivotColumn('seed')) {
                        $pivot['seed'] = $player['seed'];
                    }
                    if (array_key_exists('order', $player) && $object->players()->hasPivotColumn('order')) {
                        $pivot['order'] = $player['order'];
                    }
                    if ($object->players->contains(function ($value) use ($team) {
                        return $value->id === $team->id;
                    })) {
                        $object->players()->updateExistingPivot($team->id, $pivot);
                        continue;
                    }
                    $object->players()->attach([$team->id => $pivot]);
                    // TODO:
                    if ($this->route == 'league') {
                        if ($object->status == 4 && $object->classification === 'elo') {
                            $object->groups[0]->players()->attach([$team->id => ['player' => 1, 'score' => 1500]]);
                        }
                        // Notification::send($request->get('club')->all_admins, new NewPlayerInLeague($team, $object));
                        foreach ($request->get('club')->all_admins as $admin) {
                            $admin->notify((new NewPlayerInLeague($team, $object))->locale($admin->lang));
                        }
                    }
                    if ($this->route == 'tournament') {
                       // Notification::send($request->get('club')->all_admins, new NewPlayerInTournament($team, $object));
                        foreach($request->get('club')->all_admins as $admin) {
                            $admin->notify((new NewPlayerInTournament($team, $object))->locale($admin->lang));
                        }
                    }
                    if ($this->route === 'league_group' && $object->league->status == 4) {
                        $object->players()->updateExistingPivot($team->id, ['score' => 0]);
                        /** @var League\Group $object */
                        $object->league->players()->attach($team->id);
                        if ($object->league->classification === 'pyramid') {
                            $c = new CreateLeagueGames();
                            $c->handle($object->league);
                        }
                    }
                    if ($object->thread) {
                        foreach ($team->players as $p) {
                            $object->thread->players()->syncWithoutDetaching($p->id);
                        }
                    }

                }
            }
        }
        $admins = $request->input('admins');
        if ($admins) {
            foreach ($admins as $player) {
                if (! $object->admins->contains($player['id'])) {
                    $object->admins()->attach([$player['id'] => ['admin' => true]]);
                    if ($object->thread) {
                        $object->thread->players()->syncWithoutDetaching($player['id']);
                    }
                }
            }
        }
        $freeze = $request->input('freeze');
        if ($freeze) {
            foreach ($freeze as $player) {
                if (! $object->players->contains($player['id'])) {
                    $object->players()->attach([$player['id'] => ['freeze' => 1]]);
                } else {
                    $object->players()->updateExistingPivot($player['id'], ['freeze' => 1]);
                }
            }
        }
        return TeamResource::collection($object->players);
    }

    public function show(League $league, Team $player)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, Team $player)
    {
        $object = $this->model::findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id, $player)
    {
        $object = $this->model::findOrFail($id);
        $admin = $request->input('admin');
        $players = $request->input('player');
        $freeze = $request->input('freeze');
        if ($admin) {
            $object->admins()->detach($player);
            if ($object->thread) {
                $object->thread->players()->detach($player);
            }
        }
        if ($players) {
            $object->players()->detach($player);
            if ($object->thread) {
                $team = Team::find($player);
                foreach ($team->players as $p) {
                    $object->thread->players()->detach($p);
                }
            }
            // TODO:
            if ($this->route == 'league') {
                if ($object->status == 4 && $object->classification === 'elo') {
                    $object->groups[0]->players()->detach($player);
                }
                foreach ($object->groups as $group) {
                    if ($group->thread) {
                        $team = Team::find($player);
                        foreach ($team->players as $p) {
                            $object->thread->players()->detach($p);
                        }
                    }
                }
            }
            if ($this->route == 'league_group') {
                $games = Game::query()
                    ->where('type_type', 'App\Models\League\Group')
                    ->where('type_id', $object->id)
                    ->whereHas('players', function ($q) use ($player) {
                        $q->where('teams.id', $player);
                    })->delete();
            }
        }
        if ($freeze) {
            /** League\Group $object */
            $object->players()->updateExistingPivot($player, ['freeze' => null]);
        }
        if (! $admin && ! $players) {
           // $object->admins()->detach($player);
           // $object->players()->detach($player);
        }

        return response()->noContent();
    }

    public function clearPlayers($id)
    {
        /** @var League $object */
        $object = $this->model::findOrFail($id);
        $object->players()->detach();
        $object->thread()->detach();
    }
}
