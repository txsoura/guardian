<?php

namespace App\Observers;

use App\Http\Helpers\ActivityLog;
use App\Models\RolePermission;

class RolePermissionObserver
{
    /**
     * Handle the RolePermission "created" event.
     *
     * @param RolePermission $rolePermission
     * @return void
     */
    public function created(RolePermission $rolePermission)
    {
        ActivityLog::createActivityLog('role_permission_store', 'role_permission_store_description', $rolePermission->getTable(), $rolePermission->id, request(), $rolePermission->toArray());
    }

    /**
     * Handle the RolePermission "updated" event.
     *
     * @param RolePermission $rolePermission
     * @return void
     */
    public function updated(RolePermission $rolePermission)
    {
        ActivityLog::createActivityLog('role_permission_update', 'role_permission_update_description', $rolePermission->getTable(), $rolePermission->id, request(), $rolePermission->getOriginal(), $rolePermission->getChanges());
    }

    /**
     * Handle the RolePermission "deleted" event.
     *
     * @param RolePermission $rolePermission
     * @return void
     */
    public function deleted(RolePermission $rolePermission)
    {
        ActivityLog::createActivityLog('role_permission_destroy', 'role_permission_destroy_description', $rolePermission->getTable(), $rolePermission->id, request());
    }

    /**
     * Handle the RolePermission "restored" event.
     *
     * @param RolePermission $rolePermission
     * @return void
     */
    public function restored(RolePermission $rolePermission)
    {
        ActivityLog::createActivityLog('role_permission_restore', 'role_permission_restore_description', $rolePermission->getTable(), $rolePermission->id, request());
    }

    /**
     * Handle the RolePermission "force deleted" event.
     *
     * @param RolePermission $rolePermission
     * @return void
     */
    public function forceDeleted(RolePermission $rolePermission)
    {
        ActivityLog::createActivityLog('role_permission_force_destroy', 'role_permission_force_destroy_description', $rolePermission->getTable(), $rolePermission->id, request());
    }
}
