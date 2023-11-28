<?php

namespace App\Notifications;

use App\Channels\FcmChannel;
use App\Models\SchoolGroup;
use App\Models\User;
use App\Traits\NotificationVia;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\WebpushConfig;
use NotificationChannels\Fcm\Resources\WebpushFcmOptions;

class SchoolGroupInvoicesCreated extends Notification implements ShouldQueue
{
    use Queueable, NotificationVia;

    private $schoolGroup;

    private $key = 'SCHOOL_GROUP_INVOICES_CREATED';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SchoolGroup $schoolGroup)
    {
        $this->schoolGroup = $schoolGroup;
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
            'user' =>  [
                'name' => $this->schoolGroup->club->name,
                'image' => $this->schoolGroup->club->logo,
            ],
            'time' => Carbon::now()->toIso8601String(),
            'link' => '/',
            'message' => __('notifications.'.$this::class.'.body', ['name' => $this->schoolGroup->name]),
            'title' => __('notifications.'.$this::class.'.title'),
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
