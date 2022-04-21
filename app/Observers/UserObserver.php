<?php

namespace App\Observers;

use App\Http\Helpers\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param User $user
     * @return void
     */
    public function creating(User $user)
    {
        $user->password = Hash::make($user->password);
    }

    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        ActivityLog::createActivityLog('user_store', 'user_store_description', $user->getTable(), $user->id, request(), $user->toArray());

        event(new Registered($user));
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        ActivityLog::createActivityLog('user_update', 'user_update_description', $user->getTable(), $user->id, request(), $user->getOriginal(), $user->getChanges());
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        ActivityLog::createActivityLog('user_destroy', 'user_destroy_description', $user->getTable(), $user->id, request());
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        ActivityLog::createActivityLog('user_restore', 'user_restore_description', $user->getTable(), $user->id, request());
    }

    /**
     * Handle the User "forceDeleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        ActivityLog::createActivityLog('user_force_destroy', 'user_force_destroy_description', $user->getTable(), $user->id, request());
    }
}
