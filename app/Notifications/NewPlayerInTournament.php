<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Team;
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

class NewPlayerInTournament extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $user;

    private $tournament;

    private $key = 'ADMIN_NEW_PLAYER_IN_TOURNAMENT';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Team $user, Tournament $tournament)
    {
        $this->user = $user;
        $this->tournament = $tournament;
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
            'link' => '/cup/'.$this->tournament->id,
            'message' => __('notifications.' . $this::class . '.body', [
                'tournament' => $this->tournament->name,
                'name' => $this->user->display_name,
            ]),
            'title' => __('notifications.' . $this::class . '.title', [
                'name' => $this->tournament->name,
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
