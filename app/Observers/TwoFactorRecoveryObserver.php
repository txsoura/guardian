<?php

namespace App\Observers;

use App\Http\Helpers\ActivityLog;
use App\Models\TwoFactorRecovery;

class TwoFactorRecoveryObserver
{
    /**
     * Handle the TwoFactorRecovery "created" event.
     *
     * @param TwoFactorRecovery $twoFactorRecovery
     * @return void
     */
    public function created(TwoFactorRecovery $twoFactorRecovery)
    {
        ActivityLog::createActivityLog('two_factor_recovery_store', 'two_factor_recovery_store_description', $twoFactorRecovery->getTable(), $twoFactorRecovery->id, request(), $twoFactorRecovery->toArray());
    }

    /**
     * Handle the TwoFactorRecovery "updated" event.
     *
     * @param TwoFactorRecovery $twoFactorRecovery
     * @return void
     */
    public function updated(TwoFactorRecovery $twoFactorRecovery)
    {
        ActivityLog::createActivityLog('two_factor_recovery_update', 'two_factor_recovery_update_description', $twoFactorRecovery->getTable(), $twoFactorRecovery->id, request(), $twoFactorRecovery->getOriginal(), $twoFactorRecovery->getChanges());
    }

    /**
     * Handle the TwoFactorRecovery "deleted" event.
     *
     * @param TwoFactorRecovery $twoFactorRecovery
     * @return void
     */
    public function deleted(TwoFactorRecovery $twoFactorRecovery)
    {
        ActivityLog::createActivityLog('two_factor_recovery_destroy', 'two_factor_recovery_destroy_description', $twoFactorRecovery->getTable(), $twoFactorRecovery->id, request());
    }

    /**
     * Handle the TwoFactorRecovery "restored" event.
     *
     * @param TwoFactorRecovery $twoFactorRecovery
     * @return void
     */
    public function restored(TwoFactorRecovery $twoFactorRecovery)
    {
        ActivityLog::createActivityLog('two_factor_recovery_restore', 'two_factor_recovery_restore_description', $twoFactorRecovery->getTable(), $twoFactorRecovery->id, request());
    }

    /**
     * Handle the TwoFactorRecovery "force deleted" event.
     *
     * @param TwoFactorRecovery $twoFactorRecovery
     * @return void
     */
    public function forceDeleted(TwoFactorRecovery $twoFactorRecovery)
    {
        ActivityLog::createActivityLog('two_factor_recovery_force_destroy', 'two_factor_recovery_force_destroy_description', $twoFactorRecovery->getTable(), $twoFactorRecovery->id, request());
    }
}
