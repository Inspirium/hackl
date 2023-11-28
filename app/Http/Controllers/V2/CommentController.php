<?php

namespace App\Http\Controllers\V2;

use App\Events\ClassifiedCommentAdded;
use App\Events\ResultCommentAdded;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Notifications\NewClassifiedComment;
use App\Notifications\NewResultComment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    private $route = 'classified';

    private $model = 'App\Models\Classified';

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
        if ($request->route()) {
            $name = explode('.', $request->route()->getName())[0];
            switch ($name) {
                case 'classified':
                    $this->route = 'classified';
                    $this->model = 'App\Models\Classified';
                    break;
                case 'result':
                    $this->route = 'result';
                    $this->model = 'App\Models\Result';
                    break;
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return CommentCollection
     */
    public function index(Request $request, $id)
    {
        $comments = QueryBuilder::for(Comment::class)
            ->where('commentable_type', $this->model)
            ->where('commentable_id', $id)
            ->paginate($request->input('limit'))
            ->appends($request->query());
        $notification = Auth::user()->unreadNotifications()
            ->where('data', 'LIKE', '%"id":'.$id.'%')->first();
        if ($notification) {
            $notification->delete();
        }

        return new CommentCollection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return CommentResource
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'message' => 'required',
        ]);
        $validated['commentable_type'] = $this->model;
        $validated['commentable_id'] = $id;
        $validated['player_id'] = Auth::id();

        $comment = Comment::create($validated);
        $comment->load('commentable');
        $object = $comment->commentable;
        $object->load('comments');
        $users = $object->comments->mapWithKeys(function ($item) {
            return [$item->player->id => $item->player];
        })->unique();

        if ($this->route === 'result') {
            //$users = collect([]);
            foreach ($object->players as $team) {
                foreach ($team->players as $player) {
                    $users->put($player->id, $player);
                }
            }
        }
        if ($this->route === 'classified') {
            $users = $users->put($object->user->id, $object->user);
        }
        $users = $users->unique();
        if ($this->route === 'classified') {
            // Notification::send($users, new NewClassifiedComment($comment));
            foreach ($users as $user) {
                $user->notify((new NewClassifiedComment($comment))->locale($user->lang));
            }
            broadcast(new ClassifiedCommentAdded($comment));
        }
        if ($this->route === 'result') {
            //Notification::send($users, new NewResultComment($comment));
            foreach($users as $user) {
                $user->notify((new NewResultComment($comment))->locale($user->lang));
            }
            broadcast(new ResultCommentAdded($comment));
        }

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return CommentResource
     */
    public function show($id, Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Comment $comment)
    {
        $comment->delete();

        return response()->noContent();
    }
}
