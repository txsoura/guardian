<?php

namespace App\Observers;

use App\Http\Helpers\ActivityLog;
use App\Models\Permission;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function created(Permission $permission)
    {
        ActivityLog::createActivityLog('permission_store', 'permission_store_description', $permission->getTable(), $permission->id, request(), $permission->toArray());
    }

    /**
     * Handle the Permission "updated" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function updated(Permission $permission)
    {
        ActivityLog::createActivityLog('permission_update', 'permission_update_description', $permission->getTable(), $permission->id, request(), $permission->getOriginal(), $permission->getChanges());
    }

    /**
     * Handle the Permission "deleted" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function deleted(Permission $permission)
    {
        ActivityLog::createActivityLog('permission_destroy', 'permission_destroy_description', $permission->getTable(), $permission->id, request());
    }

    /**
     * Handle the Permission "restored" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function restored(Permission $permission)
    {
        ActivityLog::createActivityLog('permission_restore', 'permission_restore_description', $permission->getTable(), $permission->id, request());
    }

    /**
     * Handle the Permission "force deleted" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function forceDeleted(Permission $permission)
    {
        ActivityLog::createActivityLog('permission_force_destroy', 'permission_force_destroy_description', $permission->getTable(), $permission->id, request());
    }
}
