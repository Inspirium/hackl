<?php

namespace App\Notifications;

use App\Mail\ResetPassword;
use App\Models\Club;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $token;

    public $club;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, Club $club)
    {
        $this->token = $token;
        $this->club = Club::find(26);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        \App::setLocale($notifiable->lang);
        $path = 'https://my.tenis.plus/login/reset/'.$this->token;

        return (new ResetPassword($path, $this->club))
            ->to($notifiable->email)
            ->subject('Obnovite lozinku')
            ->from('info@tennis.plus', 'Tennis.plus');

    }
}
