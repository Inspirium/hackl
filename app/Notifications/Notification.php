<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\User;
use App\Traits\NotificationVia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification as IlluminateNotification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class Notification extends IlluminateNotification
{
    use Queueable, NotificationVia;

    private $user;
    private $message;

    private $title;

    private $link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $title, $message, $link = null)
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
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
        return ['database', FcmChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user' => [
                'name' => $this->user->display_name,
                'image' => $this->user->image,
            ],
            'time' => Carbon::now()->toIso8601String(),
            'link' => $this->link ?? '/',
            'message' => $this->message,
            'title' => $this->title,
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
