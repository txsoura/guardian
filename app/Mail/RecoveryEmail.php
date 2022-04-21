<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;

class RecoveryEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email, $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $url)
    {
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.recovery_email')->subject(Lang::get('mail.recovery_email.subject'))->with([$this->email, $this->url]);
    }
}
