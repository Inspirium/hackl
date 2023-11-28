<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Reservation;
use App\Models\User;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class WatcherAnnounced extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $reservation;

    private $user;

    private $key = 'WATCHER_ANNOUNCED';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, User $user)
    {
        $this->reservation = $reservation;
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
            'time' => $this->reservation->created_at->toIso8601String(),
            'link' => '/courts/'.$this->reservation->court_id.'/'.$this->reservation->from->format('Y-m-d'),
            'image' => $this->user->image,
            'title' => __('notifications.'.$this::class.'.title'),
            'message' => __('notifications.'.$this::class.'.body', [
                'firstName' => $this->user->first_name,
                'lastName' => $this->user->last_name,
                'time' => $this->reservation->from->format('H:i d.m.Y.'),
                'court' => $this->reservation->court->name,
            ]),
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
