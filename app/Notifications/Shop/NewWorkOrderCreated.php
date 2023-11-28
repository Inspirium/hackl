<?php

namespace App\Notifications\Shop;

use App\Models\User;
use App\Models\WorkOrder;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;

class NewWorkOrderCreated extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $user;

    private $workOrder;

    private $key = 'NEW_WORK_ORDER_CREATED';

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
            'link' => '/admin/workorders/' . $this->workOrder->id,
            'message' => __('notifications.' . $this::class . '.body', ['name' => $this->user->display_name]),
            'title' => __('notifications.' . $this::class . '.title'),
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
