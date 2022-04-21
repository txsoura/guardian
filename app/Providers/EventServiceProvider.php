<?php

namespace App\Providers;

use App\Models\AccessToken;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\TwoFactorRecovery;
use App\Models\User;
use App\Observers\AccessTokenObserver;
use App\Observers\PermissionObserver;
use App\Observers\RoleObserver;
use App\Observers\RolePermissionObserver;
use App\Observers\TwoFactorRecoveryObserver;
use App\Observers\UserObserver;
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

        User::observe(UserObserver::class);
        AccessToken::observe(AccessTokenObserver::class);
        Permission::observe(PermissionObserver::class);
        Role::observe(RoleObserver::class);
        RolePermission::observe(RolePermissionObserver::class);
        TwoFactorRecovery::observe(TwoFactorRecoveryObserver::class);
    }
}
