<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly string $token)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expires = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
                    ->greeting('Hello ' . $notifiable->name)
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Reset Password', $this->resetUrl($notifiable))
                    ->line("This password reset link will expire in $expires minutes.")
                    ->line('If you did not request a password reset, no further action is required.')
                    ->line('Thank you for using our application!')
                    ->salutation(new HtmlString('Regards,<br>The Zone 2 team'));
    }

    protected function resetUrl(mixed $notifiable): string
    {
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
