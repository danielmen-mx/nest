<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * Build email to reset password.
     */
    public function toMail($notifiable)
    {
        $url = config('app.frontend_url') . '/reset-password?token=' 
             . $this->token . '&email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('Recupera tu contraseña')
            ->line('Has solicitado recuperar tu contraseña.')
            ->action('Recuperar contraseña', $url)
            ->line('Si no solicitaste este cambio, ignora este correo.');
    }
}
