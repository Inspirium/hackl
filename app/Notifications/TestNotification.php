<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidMessagePriority;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class TestNotification extends Notification
{
    use Queueable;

    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class];
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
            'link' => 'https://app.tenis.plus/results/1',
            'message' => 'je poslao testnu notifikaciju',
            'title' => __('notifications.'.$this::class.'.title', ['name' => 'test']),
            'body' => __('notifications.'.$this::class.'.body', [ 'firstName' => $this->user->first_name, 'lastName' => $this->user->last_name]),
        ];
    }

    public function toFcm($notifiable)
    {
        $data = $this->toArray($notifiable);
        $message = FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($data['title'])
                ->setBody($data['body'])
            )
            ->setData(['url' => 'https://zapresic.tenis.plus/news']);

        return $message;
    }
}
