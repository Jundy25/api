<?php

namespace App\Notifications;

// App\Notifications\CustomResetPasswordNotification.php

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Your Reset Password Subject')
        ->line('<p style="color: #333;">The introduction to the notification.</p>')
        ->action('Reset Password', url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
        ->line('<p style="color: #333;">If you did not request a password reset, no further action is required.</p>');
}


}
