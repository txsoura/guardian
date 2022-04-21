<?php

namespace App\Observers;

use App\Http\Helpers\ActivityLog;
use App\Models\Role;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param Role $role
     * @return void
     */
    public function created(Role $role)
    {
        ActivityLog::createActivityLog('role_store', 'role_store_description', $role->getTable(), $role->id, request(), $role->toArray());
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param Role $role
     * @return void
     */
    public function updated(Role $role)
    {
        ActivityLog::createActivityLog('role_update', 'role_update_description', $role->getTable(), $role->id, request(), $role->getOriginal(), $role->getChanges());
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param Role $role
     * @return void
     */
    public function deleted(Role $role)
    {
        ActivityLog::createActivityLog('role_destroy', 'role_destroy_description', $role->getTable(), $role->id, request());
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param Role $role
     * @return void
     */
    public function restored(Role $role)
    {
        ActivityLog::createActivityLog('role_restore', 'role_restore_description', $role->getTable(), $role->id, request());
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param Role $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
        ActivityLog::createActivityLog('role_force_destroy', 'role_force_destroy_description', $role->getTable(), $role->id, request());
    }
}
