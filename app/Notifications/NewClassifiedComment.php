<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\User;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class NewClassifiedComment extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $comment;

    private $key = 'CLASSIFIED_NEW_MESSAGE';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->comment->load(['player', 'commentable']);
        \App::setLocale($notifiable->lang);
        return [
            'user' => [
                'name' => $this->comment->player->display_name,
                'image' => $this->comment->player->image,
            ],
            'time' => $this->comment->created_at->toIso8601String(),
            'link' => $this->comment->commentable->link,
            'title' => __('notifications.' . $this::class . '.title', ['title' => $this->comment->commentable->title]),
            'id' => $this->comment->id,
            'message' => $this->comment->message,
            'image' => $this->comment->player->image,
        ];
    }

    public function toFcm($notifiable)
    {
        $data = $this->toArray($notifiable);
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($data['title'])
                ->setBody($data['message'])
            )
            ->setData(['url' => $data['link']]);
    }
}
