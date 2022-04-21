<?php

namespace App\Observers;

use App\Http\Helpers\ActivityLog;
use App\Models\AccessToken;

class AccessTokenObserver
{
    /**
     * Handle the AccessToken "created" event.
     *
     * @param AccessToken $accessToken
     * @return void
     */
    public function created(AccessToken $accessToken)
    {
        ActivityLog::createActivityLog('access_token_store', 'access_token_store_description', $accessToken->getTable(), $accessToken->id, request(), $accessToken->toArray());

    }

    /**
     * Handle the AccessToken "updated" event.
     *
     * @param AccessToken $accessToken
     * @return void
     */
    public function updated(AccessToken $accessToken)
    {
        ActivityLog::createActivityLog('access_token_update', 'access_token_update_description', $accessToken->getTable(), $accessToken->id, request(), $accessToken->getOriginal(), $accessToken->getChanges());
    }

    /**
     * Handle the AccessToken "deleted" event.
     *
     * @param AccessToken $accessToken
     * @return void
     */
    public function deleted(AccessToken $accessToken)
    {
        ActivityLog::createActivityLog('access_token_destroy', 'access_token_destroy_description', $accessToken->getTable(), $accessToken->id, request());
    }

    /**
     * Handle the AccessToken "restored" event.
     *
     * @param AccessToken $accessToken
     * @return void
     */
    public function restored(AccessToken $accessToken)
    {
        ActivityLog::createActivityLog('access_token_restore', 'access_token_restore_description', $accessToken->getTable(), $accessToken->id, request());
    }

    /**
     * Handle the AccessToken "force deleted" event.
     *
     * @param AccessToken $accessToken
     * @return void
     */
    public function forceDeleted(AccessToken $accessToken)
    {
        ActivityLog::createActivityLog('access_token_force_destroy', 'access_token_force_destroy_description', $accessToken->getTable(), $accessToken->id, request());
    }
}
