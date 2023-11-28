<?php

namespace App\Notifications;

use App\Models\Club;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessage extends Notification implements ShouldQueue
{
    use Queueable;

    private $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //\App::setLocale($notifiable->lang);
        return ['mail'];
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
            //
        ];
    }

    public function toMail($notifiable)
    {
        \App::setLocale($notifiable->lang);
        return (new \App\Mail\ContactMessage($this->request))->to($notifiable->email);
    }


}
