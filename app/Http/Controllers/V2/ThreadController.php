<?php

namespace App\Http\Controllers\V2;

use App\Actions\NewMessageAction;
use App\Actions\Query\UserQuery;
use App\Http\Resources\ThreadResource;
use App\Models\Team;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $threads = QueryBuilder::for(Thread::class)
            ->allowedFilters([
                'title',
                AllowedFilter::exact('players.id')->default(Auth::id()),
                AllowedFilter::callback('players_exact', function ($query, $value) {
                    $query->has('players', '=', count($value));
                    foreach ($value as $id) {
                        $query->whereHas('players', function ($q) use ($id) {
                            $q->where('users.id', $id);
                        });
                    }
                }),
            ])
            ->allowedSorts([
                AllowedSort::field('date', 'updated_at'),
                AllowedSort::field('created_at'),
            ])
            ->defaultSort('-updated_at')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());

        return ThreadResource::collection($threads);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return ThreadResource
     *
     * @mixin User $user
     */
    public function store(Request $request, NewMessageAction $newMessageAction, UserQuery $userQuery)
    {
        $players = $request->input('players');
        $title = $request->has('title') ? $request->input('title') : '';
        $thread = null;
        if ($players) {
            $user = Auth::user();
            if (count($players) > 1) {
                //create new group
                $thread = Thread::create(['title' => $title]);
                $ids = [];
                foreach ($players as $player) {
                    if ($player['type'] === 'player') {
                        $ids[] = $player['id'];
                    } else {
                        $team = Team::find($player['id']);
                        foreach ($team->players as $p) {
                            $ids[] = $p->id;
                        }
                    }
                }
                $ids[] = Auth::id();
                $thread->players()->attach($ids);
            } else {
                $id = $players[0]['id'];
                if ($players[0]['type'] === 'team') {
                    $team = Team::find($players[0]['id']);
                    if (!$team) {
                        return;
                    }
                    $id = $team->primary_contact_id;
                }
                //try to find existing conversation
                $thread = $user->threads()->has('players', '=', 2)->whereHas('players', function ($query) use ($id) {
                    $query->where('player_id', $id);
                })->first();
                if (!$thread) {
                    $thread = Thread::create(['title' => $title, 'club_id' => $request->get('club')->id]);
                    $thread->players()->attach([Auth::id(), $id]);
                }
            }
        }
        if ($request->input('filter')) {
            $players = $userQuery->get($request, -1);
            if (!$thread) {
                $thread = Thread::create(['title' => $title]);
            }
            $players->merge([Auth::user()]);
            $thread->players()->attach($players);
        }
        $newMessageAction->execute($thread);

        return new ThreadResource($thread);
    }

    /**
     * Display the specified resource.
     *
     * @param  Thread  $thread
     * @return ThreadResource
     */
    public function show(Thread $thread)
    {
        return new ThreadResource($thread);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Thread  $thread
     * @return ThreadResource
     */
    public function update(Request $request, Thread $thread)
    {
        if ($request->has('title')) {
            $thread->update(['title' => $request->input('title')]);
        }
        if ($request->has('players')) {
            $players = collect($request->input('players'))->map(function ($item) {
            return $item['id'];
            });
            $thread->players()->sync($players);
        }

        return new ThreadResource($thread);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Thread  $thread
     * @return Response
     */
    public function destroy(Thread $thread)
    {
        $thread->delete();

        return response()->noContent();
    }
}
