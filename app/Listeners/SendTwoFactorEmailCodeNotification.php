<?php

namespace App\Listeners;

use App\Events\TwoFactorVerify;
use App\Mail\TwoFactorVerify as MailTwoFactorVerify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTwoFactorEmailCodeNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  TwoFactorVerify  $event
     * @return void
     */
    public function handle(TwoFactorVerify $event)
    {
        Mail::to($event->user->email)->send(new MailTwoFactorVerify($event->code));
    }
}
