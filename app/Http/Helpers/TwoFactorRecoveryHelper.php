<?php

namespace App\Http\Helpers;

use App\Models\TwoFactorRecovery;
use App\Models\User;
use Ramsey\Uuid\Uuid;

class TwoFactorRecoveryHelper
{
    /**
     * get first or create user two factor recovery code
     *
     * @param User $user
     * @return string
     */
    public static function firstOrCreate(User $user): string
    {
        $recovery = TwoFactorRecovery::firstOrCreate([
            'user_id' => $user->id,
            'used' => false
        ], [
            'code' => Uuid::uuid4(),
            'user_id' => $user->id,
        ]);

        return $recovery->code;
    }

    /**
     * delete user two factor recovery code
     *
     * @param User $user
     */
    public static function delete(User $user)
    {
        TwoFactorRecovery::where('user_id', $user->id)
            ->where('used', false)
            ->delete();
    }

    /**
     * Check recovery token & disable two factor
     *
     * @param User $user
     * @param string $code
     * @return boolean
     */
    public static function recovery(User $user, string $code): bool
    {
        $twoFactorRecovery = TwoFactorRecovery::where('code', $code)
            ->where('user_id', $user->id)
            ->where('used', false)
            ->first();

        if ($twoFactorRecovery) {
            $twoFactorRecovery->used = true;
            $twoFactorRecovery->update();

            $user->two_factor_provider = null;
            $user->update();

            return true;
        }

        return false;
    }
}
