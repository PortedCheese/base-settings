<?php

namespace PortedCheese\BaseSettings\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginLink extends Notification
{
    use Queueable;

    protected $link;

    /**
     * Create a new notification instance.
     *
     * @param $token
     * @return void
     */
    public function __construct(\PortedCheese\BaseSettings\Models\LoginLink $link)
    {
        $this->link = $link;
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
        return (new MailMessage)
            ->subject('Одноразовый вход на сайт')
            ->line('Вы получили это письмо потому что Мы получили запрос на одноразовый вход на сайт.')
            ->action('Войти', route('profile.auth.email-authenticate',['token' => $this->link->token]))
            ->line('Если Вы не отправляли запроса, игнорируйте это письмо.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
