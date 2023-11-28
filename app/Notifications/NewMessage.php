<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Message;
use App\Models\Thread;
use App\Models\User;
use App\Traits\NotificationVia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    private $thread;

    private $user;

    private $message;

    private $key = 'MESSAGE_NEW';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Thread $thread, User $user, $message = null)
    {
        $this->thread = $thread;
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        \App::setLocale($notifiable->lang);
        //check if user already has notification
        /*if ($notifiable->unreadNotifications()->where('type', 'App\Notifications\NewMessage')->where('data', 'LIKE', '%"id":'.$this->thread->id.'%')->count()) {
            return [];
        }*/

        if (
            (isset($notifiable->notifications_settings[$this->key]) && $notifiable->notifications_settings[$this->key])
            || ! isset($notifiable->notifications_settings[$this->key])
        ) {
            return [FcmChannel::class];
        }
        return [];
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
            'time' => Carbon::now()->toIso8601String(),
            'link' => $this->thread->link,
            'title' => __('notifications.'. $this::class .'.title', ['name' => $this->user->display_name]),
            'message' => $this->message,
            'id' => $this->thread->id,
            'image' => $this->user->image
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
