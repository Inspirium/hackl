<?php

namespace App\Notifications;

use App\Models\Result;
use App\Models\User;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class RequestResultVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $result;

    private $user;

    private $key = 'RESULT_VERIFICATION';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Result $result, User $user)
    {
        $this->result = $result;
        $this->user = $user;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        \App::setLocale($notifiable->lang);
        return [
            'user' => [
                'name' => $this->user->display_name,
                'image' => $this->user->image,
            ],
            'time' => $this->result->created_at->toIso8601String(),
            'link' => $this->result->link,
            'message' => __('notifications.'.$this::class.'.body', [
                'name' => $this->user->display_name,
            ]),
            'title' => __('notifications.'.$this::class.'.title'),
            'image' => $this->user->image,
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
