<?php

namespace App\Notifications;

use App\Models\News;
use App\Traits\NotificationVia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class NewNews extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $news;

    private $key = 'NEW_NEWS';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(News $news)
    {
        $this->news = $news;
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
                'name' => $this->news->club->name,
                'image' => $this->news->club->logo,
            ],
            'time' => $this->news->created_at->toIso8601String(),
            'link' => '/news/'.$this->news->id,
            'title' => __('notifications.'.$this::class.'.title'),
            'message' => $this->news->title,
            'image' => $this->news->club->logo,
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
