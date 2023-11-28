<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Tournament;
use App\Models\User;
use App\Traits\NotificationVia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class TournamentStart extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $tournament;

    private $user;

    private $key = 'TOURNAMENT_START';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Tournament $tournament, User $user)
    {
        $this->tournament = $tournament;
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
            'time' => Carbon::now()->toIso8601String(),
            'link' => $this->tournament->link,
            'message' => __('notifications.'.$this::class.'.body', [
                'tournament' => $this->tournament->name,
            ]),
            'title' => __('notifications.'.$this::class.'.title'),
            'image' => $this->tournament->club->logo,
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
