<?php

namespace App\Notifications;

use App\Models\Reservation;
use App\Models\User;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class InvitedToReservation extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $reservation;

    private $user;

    private $key = 'RESERVATION_INVITE';

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
                'name' => $this->reservation->players[0]->display_name,
                'image' => $this->reservation->players[0]->image,
            ],
            'time' => $this->reservation->created_at->toIso8601String(),
            'link' => '/courts/'.$this->reservation->court_id.'/'.$this->reservation->from->format('Y-m-d'),
            'title' => __('notifications.'.$this::class.'.title'),
            'image' => $this->reservation->players[0]->image,
            'message' => __('notifications.'.$this::class.'.body', [
                'name' => $this->reservation->players[0]->display_name,
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
