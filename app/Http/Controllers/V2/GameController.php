<?php

namespace App\Http\Controllers\V2;

use App\Actions\ScoreGame;
use App\Events\LiveResultUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class GameController extends Controller
{
    private $route = 'league';

    private $model = 'App\Models\League';

    public function __construct(Request $request)
    {
        //$this->middleware('auth:api');
        if ($request->route()) {
            $name = explode('.', $request->route()->getName())[0];
            switch ($name) {
                case 'league_group':
                    $this->route = 'league_group';
                    $this->model = 'App\Models\League\Group';
                    break;
                case 'tournament':
                    $this->route = 'tournament';
                    $this->model = 'App\Models\Tournament';
                    break;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null)
    {
        if ($id) {
            $m = $this->model::find($id);
            $s = $m->games();
        }
         else {
             $s = Game::class;
         }
        $games = QueryBuilder::for($s)
            ->allowedFilters([
                AllowedFilter::callback('players', function ($query, $player) {
                    $query->whereHas('players', function ($q) use ($player) {
                        $q->where('participant_id', $player)->where('participant_type', Team::class);
                    });
                }),
                AllowedFilter::callback('tournament', function ($query, $value) {
                    return $query->where('type_type', 'App\Models\TournamentRound')
                        ->whereHas('type', function ($q) use ($value) {
                       return $q->whereHas('tournament', function($q) use ($value) {
                           return $q->where('id', $value);
                       });
                    });
                }),

                AllowedFilter::exact('type', 'type_type'),
                AllowedFilter::callback('not_played', function($query) {
                    return $query->whereNull('played_at');
                }),
                AllowedFilter::callback('player', function ($query, $player) {
                    $query->whereHas('players', function ($q) use ($player) {
                        $q->whereHas('players', function($q) use ($player) {
                            $q->where('player_team.player_id', $player);
                        });
                    });
                }),
            ])
            ->paginate($request->input('limit'))
            ->appends($request->query());

        return GameCollection::make($games);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        $game = QueryBuilder::for(Game::where('id', $game))
            ->allowedIncludes(['reservation', 'result', 'players'])
            ->first();

        return GameResource::make($game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'is_surrendered' => 'sometimes',
        ]);
        if (in_array($game->is_surrendered, [1, 2]) && ($validated['is_surrendered'] != $game->is_surrendered)) {
            // game was played, restore
            $validated['played_at'] = null;
            $game->played_at = null;
            // ScoreGame::unscore($game);
            $game->is_surrendered = $validated['is_surrendered'];
            $game->save();
        }
        if (in_array($validated['is_surrendered'], [1, 2])) {
            $game->played_at = Carbon::now();
            $game->is_surrendered = $validated['is_surrendered'];
            $game->save();
            ScoreGame::score($game);
        } else {
            $game->is_surrendered = $validated['is_surrendered'];
            $game->save();
        }
        if ($request->input('live')) {
            if ($request->input('live') === 'update') {
                $game->live = true;
                $game->live_data = $request->input('live_data');
                $game->save();
                broadcast(new LiveResultUpdated($game));
            }
            if ($request->input('live') === 'end') {
                // TODO: create final result
            }
        }

        return GameResource::make($game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        $game->delete();

        return response()->noContent();
    }
}
