<?php

namespace App\Http\Helpers;

use App\Enums\TwoFactorProvider;
use App\Enums\UserStatus;
use App\Events\TwoFactorVerify;
use App\Models\TwoFactorToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class TwoFactorHelper
{
    // Verify user
    public static function verify($id, $code)
    {
        $user = User::where('id', $id)->where('status', '!=', UserStatus::BLOCKED)->where('two_factor_provider', '!=', null)->firstOrFail();

        switch ($user->two_factor_provider) {
            case TwoFactorProvider::SMS:
                $verification = TwilioHelper::verify()->verificationChecks->create($code, array('to' =>  '+' . $user->cellphone));

                if (!$verification->valid) {
                    return  false;
                }

                return true;
                break;

            case TwoFactorProvider::MAIL:
                if (TwoFactorHelper::checkToken($user, $code)) {
                    return true;
                }

                return false;
                break;

            default:
                return false;
                break;
        }

        return false;
    }

    // send user verify code
    public static function send($id)
    {
        $user = User::where('id', $id)->where('status', '!=', UserStatus::BLOCKED)->where('two_factor_provider', '!=', null)->firstOrFail();

        switch ($user->two_factor_provider) {
            case TwoFactorProvider::SMS:
                TwilioHelper::verify()->verifications->create('+' . $user->cellphone, TwoFactorProvider::SMS);
                return  new JsonResponse([
                    'message' => trans('cellphone.sent'),
                    'email' => $user->email,
                    'provider' => TwoFactorProvider::SMS
                ], 200);
                break;

            case TwoFactorProvider::MAIL:
                $code = Str::random(6);

                TwoFactorToken::create([
                    'expiration' => Carbon::parse(now())->addMinute(5),
                    'code' => $code,
                    'user_id' => $user->id,
                    'provider' => TwoFactorProvider::MAIL
                ]);

                event(new TwoFactorVerify($user, $code));
                return  new JsonResponse([
                    'message' => trans('email.sent'),
                    'email' => $user->email,
                    'provider' => TwoFactorProvider::MAIL
                ], 200);
                break;

            default:
                return false;
                break;
        }
    }

    //Check token in two_factor_token table
    public static function checkToken(User $user, $code)
    {
        $twoFactorToken = TwoFactorToken::where('provider', $user->two_factor_provider)
            ->where('code', $code)
            ->where('expiration', '>=', now())
            ->where('user_id', $user->id)
            ->where('used', false)
            ->first();

        if ($twoFactorToken) {
            $twoFactorToken->used = true;
            $twoFactorToken->update();

            return true;
        }

        return false;
    }
}
