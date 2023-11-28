<?php

namespace App\Notifications;

use App\Models\PlayerProfile;
use App\Models\Reservation;
use App\Models\User;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class NewProfileData extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $profile;


    private $key = 'PROFILE_UPDATED';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PlayerProfile $playerProfile)
    {
        $this->profile = $playerProfile;
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
                'name' => $this->profile->owner->display_name,
                'image' => $this->profile->owner->image,
            ],
            'time' => $this->profile->created_at->toIso8601String(),
            'link' => '/player/'.$this->profile->player->id.'/profile?profiledBy='.$this->profile->owner->id,
            'message' => __('notifications.'.$this::class.'.body', [
                'name' => $this->profile->owner->display_name,
            ]),
            'title' => __('notifications.'.$this::class.'.title'),
            'image' => $this->profile->owner->image,
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
