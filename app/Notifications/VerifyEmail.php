<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail
{
    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var string|null
     */
    protected  $urlCallback;

    /**
     * Create a notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->urlCallback = Config::get('app.view_url');
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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject(Lang::get('mail.verify_email.subject'))
            ->line(Lang::get('mail.verify_email.line_one'))
            ->action(Lang::get('mail.verify_email.action'), $verificationUrl)
            ->line(Lang::get('mail.verify_email.line_two'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $id = $notifiable->getKey();

        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $id,
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        $signedUrl = explode('/', $signedUrl);
        $signedParams = explode('?', $signedUrl[9]);

        return $this->urlCallback .
            "/auth/email/verify?" . $signedParams[1] . '&key=' . $signedParams[0] . '&id=' . $id;
    }
}
