<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailAndSetPassword extends Notification
{
    use Queueable;

    public function __construct(private string $token)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'verification.set-password',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
                'token' => $this->token,
            ]
        );

        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Click the button below to verify your email address and set your password.')
            ->action('Verify & Set Password', $url)
            ->line('If you did not request this account, no further action is required.');
    }
}
