<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\UserPassword as MailUserPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendUserPassword implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        Mail::to($event->user->email)->send(new MailUserPassword($event->password));
    }
}
