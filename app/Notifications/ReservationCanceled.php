<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Reservation;
use App\Models\User;
use App\Traits\NotificationVia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class ReservationCanceled extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $user;

    private $reservation;

    private $key = 'RESERVATION_CANCELED';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, User $user)
    {
        $this->user = $user;
        $this->reservation = $reservation;
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
            'link' => '/courts',
            'title' => __('notifications.'.$this::class.'.title'),
            'message' => __('notifications.'.$this::class.'.body', [
                'time' => $this->reservation->from->format('H:i d.m.Y.'),
                'court' => $this->reservation->court->name,
            ]),
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
