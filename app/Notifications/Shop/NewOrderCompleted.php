<?php

namespace App\Notifications\Shop;

use App\Channels\FcmChannel;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;

class NewOrderCompleted extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $order;

    private $user;

    private $key = 'ORDER_COMPLETED';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order, $user)
    {
        $this->order = $order;
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
            'time' => $this->order->updated_at->toIso8601String(),
            'link' => '/player/' . $this->order->buyer_id . '/orders/' . $this->order->id,
            'message' => __('notifications.' . $this::class . '.body', ['name' => $this->user->display_name]),
            'title' => __('notifications.' . $this::class . '.title'),
            'image' => $this->order->club->logo,
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
