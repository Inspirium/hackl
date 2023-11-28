<?php

namespace App\Actions;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\Thread;
use App\Notifications\NewMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NewMessageAction
{
    private $saveImageAction;

    public function __construct(SaveImageAction $saveImageAction)
    {
        $this->saveImageAction = $saveImageAction;
    }

    public function execute(Thread $thread)
    {
        $request = request();
        $type = $request->input('type');
        $multimedia = null;
        switch ($type) {
            case 'image':
                $message = $request->input('message');
                $multimedia = $this->saveImageAction->execute($request->input('multimedia'), 'messages');
                $notif_message = __('Nova slika');
                break;
            case 'terms':
                $message = json_encode($request->input('message'));
                $notif_message = __('Prijedlog termina');
                break;
            default:
                $message = $request->input('message');
                $notif_message = $message;
                $type = null;
                break;
        }
        $user = Auth::user();

        $message = Message::create([
            'message' => $message,
            'thread_id' => $thread->id,
            'player_id' => $user->id,
            'multimedia_type' => $type,
            'multimedia' => $multimedia,
        ]);
        $thread->touch();

        $players = $thread->players->filter(function ($item) use ($user) {
            return $item->id !== $user->id;
        });

        broadcast(new MessageSent($message));
        foreach ($players as $player) {
            $player->notify((new NewMessage($thread, $user, $notif_message))->locale($player->lang));
        }
        // Notification::send($players, new NewMessage($thread, $user, $notif_message));
    }
}
