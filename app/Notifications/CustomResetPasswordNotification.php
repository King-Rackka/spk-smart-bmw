<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;

class CustomResetPasswordNotification extends ResetPasswordBase
{
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password — BimmerGuide')
            ->greeting('Halo! 👋')
            ->line('Kami menerima permintaan untuk mereset password akun BimmerGuide kamu.')
            ->action('Reset Password Sekarang', $url)
            ->line('Link ini akan kedaluwarsa dalam 60 menit.')
            ->line('Kalau kamu tidak meminta reset password, abaikan email ini saja — passwordmu tetap aman.')
            ->salutation('Salam, Tim BimmerGuide 🚗');
    }
}