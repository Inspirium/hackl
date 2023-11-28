<?php

namespace App\Traits;

use App\Channels\FcmChannel;

trait NotificationVia
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($notifiable->notifications_settings === null) {
            return ['database', FcmChannel::class];
        }
        if (isset($notifiable->notifications_settings['ALL']) && !$notifiable->notifications_settings['ALL']) {
            return [];
        }
        if (
            (isset($notifiable->notifications_settings[$this->key]) && $notifiable->notifications_settings[$this->key])
            || ! isset($notifiable->notifications_settings[$this->key])
        ) {
            return ['database', FcmChannel::class];
        }

        return [];
    }
}
