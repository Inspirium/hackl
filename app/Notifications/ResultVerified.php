<?php

namespace App\Notifications;

use App\Models\Result;
use App\Models\User;
use App\Traits\NotificationVia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class ResultVerified extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $result;

    private $user;

    private $key = 'RESULT_VERIFIED';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Result $result, User $user = null)
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
        $image = 'https://www.gravatar.com/avatar/'.md5('sustav@tennis.plus').'?s=200&d=robohash';
        $user = [
            'name' => 'Sustav',
            'image' => $this->result->club->logo,
        ];
        if ($this->user) {
            $user = [
                'name' => $this->user->display_name,
                'image' => $this->user->image,
            ];
        }

        return [
            'user' => $user,
            'time' => Carbon::now()->toIso8601String(),
            'link' => '/result/'.$this->result->id,
            'message' => __('notifications.'.$this::class.'.body', ['name' => $user['name']]),
            'title' => __('notifications.'.$this::class.'.title'),
            'image' => $user['image'],
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
