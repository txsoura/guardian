<?php

namespace App\Providers;

use App\Events\TwoFactorVerify;
use App\Events\UserCreated;
use App\Listeners\SendTwoFactorEmailCodeNotification;
use App\Listeners\SendUserPassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TwoFactorVerify::class => [
            SendTwoFactorEmailCodeNotification::class
        ],
        UserCreated::class => [
            SendUserPassword::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
