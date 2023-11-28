<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Thread;
use App\Notifications\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MessagesController extends Controller
{
    public function getThreads()
    {
        $threads = Thread::whereHas('players', function ($query) {
            $query->where('player_id', Auth::id());
        })->get();

        return response()->json($threads);
    }

    public function getThread(Thread $thread)
    {
        $this->authorize('view', $thread);

        return response()->json($thread);
    }

    public function getMoreThread(Request $request, Thread $thread)
    {
        $offset = intval($request->input('offset'));
        $thread->load(['messages' => function ($query) use ($offset) {
            $query->offset($offset);
        }]);

        return response()->json($thread->messages);
    }

    public function postThread(Request $request)
    {
        $this->authorize('create', Thread::class);
        $thread = new Thread();
        $thread->title = $request->input('title');
        $thread->save();
        $thread->players()->attach(\Auth::id(), ['owner' => true]);
        foreach ($request->input('players') as $player) {
            $thread->players()->attach($player['id']);
        }

        $message = new Message();
        $user = Auth::user();
        $message->message = $request->input('message');
        $message->player()->associate($user);
        $message->thread()->associate($thread);
        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $path = $file->store(sprintf('%s/%d/%d', 'messages', date('Y'), date('m')), 'public');
                $message->multimedia = $path;
                $message->multimedia_type = 'image';
            }
        }
        $message->save();
        $players = $thread->players->filter(function ($item) use ($user) {
            return $item->id !== $user->id;
        });
        //Notification::send($players, new NewMessage($thread, $user));
        foreach($players as $player) {
            $player->notify((new NewMessage($thread, $user))->locale($player->lang));
        }

        return response()->json($thread);
    }

    public function deleteThread(Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->delete();

        return response()->json();
    }

    public function putThread(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);
        $thread->title = $request->input('title');
        $thread->save();

        return response()->json($thread);
    }

    public function postMessage(Request $request, Thread $thread)
    {
        $this->authorize('update', $thread);
        $message = new Message();
        $user = Auth::user();
        $message->message = $request->input('message');
        $message->player()->associate($user);
        $message->thread()->associate($thread);
        if ($request->hasFile('image') && $request->file('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $path = $file->store(sprintf('%s/%d/%d', 'messages', date('Y'), date('m')), 'public');
                $message->multimedia = $path;
                $message->multimedia_type = 'image';
            }
        }
        $message->save();
        broadcast(new MessageSent($message));
        if (! $thread->public) {
            $players = $thread->players->filter(function ($item) use ($user) {
                return $item->id !== $user->id;
            });

            // Notification::send($players, new NewMessage($thread, $user));
            foreach($players as $player) {
                $player->notify((new NewMessage($thread, $user))->locale($player->lang));
            }
        }

        return response()->json($message);
    }

    public function putMessage(Request $request, Thread $thread, Message $message)
    {
        //TODO: authorize editing message;
        $message->message = $request->input('message');
        $message->save();

        return response()->json();
    }

    public function deleteMessage(Message $message)
    {
        //TODO: authorize message delete
        $message->delete();

        return response()->json();
    }

    public function newThread($id = null)
    {
        if ($id) {
            // pull only one-to-one threads
            $threads = Thread::has('players', '=', 2)->whereHas('players', function ($query) {
                $query->where('player_id', Auth::id());
            })->get();
            // get only to the first one that has requested player
            $thread = $threads->first(function ($thread) use ($id) {
                return $thread->players->contains('id', $id);
            });
            if ($thread) {
                $this->authorize('view', $thread);

                return response()->json(['location' => '/me/message/'.$thread->id]);
            }
        }
        //no threads found, create new
        $thread = new Thread();
        $thread->save();
        $thread->players()->attach([Auth::id(), $id]);

        return response()->json(['location' => '/me/message/'.$thread->id]);
    }
}
