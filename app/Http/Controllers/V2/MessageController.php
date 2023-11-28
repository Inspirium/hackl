<?php

namespace App\Http\Controllers\V2;

use App\Actions\NewMessageAction;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Thread  $thread
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($thread)
    {
        $messages = QueryBuilder::for(Message::class)
            ->where('thread_id', $thread)
            ->orderBy('created_at', 'desc')
            ->paginate(request()->input('limit'))
            ->appends(request()->query());
        $notification = Auth::user()->unreadNotifications()
            ->where('data', 'LIKE', '%"id":'.$thread.'%')->first();
        if ($notification) {
            $notification->delete();
        }

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Thread $thread, NewMessageAction $newMessageAction)
    {
        $newMessageAction->execute($thread);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return response()->noContent();
    }
}
