<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends \Illuminate\Auth\Notifications\ResetPassword
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var string|null
     */
    protected  $urlCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->urlCallback = env('APP_VIEW_URL');
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $url = $this->urlCallback .
            '/auth/password/reset?token=' . $this->token .
            '?email=' . $notifiable->getEmailForPasswordReset();

        return (new MailMessage)
            ->subject(Lang::get('mail.reset_password.subject'))
            ->line(Lang::get('mail.reset_password.line_one'))
            ->action(Lang::get('mail.reset_password.action'), $url)
            ->line(Lang::get('mail.reset_password.line_two', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('mail.reset_password.line_three'));
    }
}
