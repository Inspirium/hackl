<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Reservation;
use App\Models\User;
use App\Models\WorkOrder;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class SpannungComplete extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $workOrder;

    private $user;

    private $key = 'SPANNUNG_COMPLETE';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(WorkOrder $workOrder, User $user)
    {
        $this->workOrder = $workOrder;
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
            'time' => $this->workOrder->updated_at->toIso8601String(),
            'link' => '/player/' . $this->workOrder->orderable->buyer_id . '/orders',
            'message' => __('notifications.' . $this::class . '.body'),
            'title' => __('notifications.' . $this::class . '.title'),
            'image' => $this->workOrder->orderable->club->logo,
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
