<?php

namespace App\Http\Helpers;

use App\Models\TwoFactorRecovery;
use App\Models\User;
use Ramsey\Uuid\Uuid;

class TwoFactorRecoveryHelper
{
    //  get first or create user two factor recovery code
    public static function firstOrCreate(User $user)
    {
        $recovery = TwoFactorRecovery::where('user_id', $user->id)->where('used', false)->first();

        if (!$recovery) {
            $recovery =  TwoFactorRecovery::create([
                'code' => Uuid::uuid4(),
                'user_id' => $user->id,
            ]);
        }

        return $recovery->code;
    }

    //  delete user two factor recovery code
    public static function delete(User $user)
    {
        $recovery = TwoFactorRecovery::where('user_id', $user->id)->where('used', false)->first();

        if ($recovery) {
            $recovery->delete();
        }

        return;
    }

    //Check recovery token & disable two factor
    public static function recovery(User $user, $code)
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
