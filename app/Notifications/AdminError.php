<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\Result;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;

class AdminError extends Notification
{
    use Queueable;

    private $title;

    private $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        \App::setLocale($notifiable->lang);
        return [FcmChannel::class, 'database'];
    }

    public function toArray($notifiable) {
        \App::setLocale($notifiable->lang);
        return [
            'title' => $this->title,
            'message' => $this->body,
        ];
    }

    public function toFcm($notifiable)
    {
        \App::setLocale($notifiable->lang);
        return FcmMessage::create()
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle($this->title)
                ->setBody($this->body)
            );
    }

}
