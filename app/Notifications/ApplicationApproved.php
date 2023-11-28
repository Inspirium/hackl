<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Club;
use App\Models\User;
use App\Traits\NotificationVia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class ApplicationApproved extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $user;

    private $club;

    private $key = 'APPLICATION_APPROVED';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Club $club)
    {
        $this->user = $user;
        $this->club = $club;
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
            'link' => $this->club->domain,
            'title' => __('notifications.'.$this::class.'.title'),
            'message' => __('notifications.'.$this::class.'.body', ['name' => $this->club->name]),
            'image' => $this->club->logo,
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
