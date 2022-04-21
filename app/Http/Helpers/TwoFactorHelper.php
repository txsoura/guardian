<?php

namespace App\Http\Helpers;

use App\Enums\TwoFactorProvider;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

class TwoFactorHelper
{
    /**
     * verify user
     *
     * @param int $id
     * @param string $code
     * @param string|null $provider
     * @return boolean
     * @throws ConfigurationException|TwilioException
     */
    public static function verify(int $id, string $code, string $provider = null): bool
    {
        $user = User::whereId($id)
            ->where('status', '!=', UserStatus::BLOCKED)
            ->when(!$provider, function ($query) {
                return $query->whereNotNull('two_factor_provider');
            })
            ->firstOrFail();

        switch ($provider ?: $user->two_factor_provider) {
            case TwoFactorProvider::SMS:
                $verification = TwilioHelper::verify()
                    ->verificationChecks
                    ->create($code, array('to' => '+' . $user->cellphone));

                if (!$verification->valid) {
                    return false;
                }

                return true;

            case TwoFactorProvider::MAIL:
                $verification = TwilioHelper::verify()
                    ->verificationChecks
                    ->create($code, array('to' => $user->email));

                if (!$verification->valid) {
                    return false;
                }

                return true;

            case TwoFactorProvider::GOOGLE_AUTHENTICATOR:
                return TotpHelper::verify($user->getTotpSecret(), $code);

            default:
                return false;
        }
    }

    /**
     * send user verify code
     *
     * @param int $id
     * @param string|null $provider
     * @return false|JsonResponse
     * @throws ConfigurationException|TwilioException
     */
    public static function send(int $id, string $provider = null)
    {
        $user = User::whereId($id)
            ->where('status', '!=', UserStatus::BLOCKED)
            ->when(!$provider, function ($query) {
                return $query->whereNotNull('two_factor_provider');
            })
            ->firstOrFail();

        switch ($provider ?: $user->two_factor_provider) {
            case TwoFactorProvider::SMS:
                TwilioHelper::verify()
                    ->verifications
                    ->create('+' . $user->cellphone, TwoFactorProvider::SMS, ["locale" => $user->lang]);

                return new JsonResponse([
                    'message' => trans('cellphone.sent'),
                    'email' => $user->email,
                    'provider' => TwoFactorProvider::SMS
                ]);

            case TwoFactorProvider::MAIL:
                TwilioHelper::verify()
                    ->verifications
                    ->create($user->email, 'email', ["locale" => $user->lang]);

                return new JsonResponse([
                    'message' => trans('email.sent'),
                    'email' => $user->email,
                    'provider' => TwoFactorProvider::MAIL
                ]);

            case TwoFactorProvider::GOOGLE_AUTHENTICATOR:
                return new JsonResponse([
                    'message' => trans('totp.verify_app'),
                    'provider' => TwoFactorProvider::GOOGLE_AUTHENTICATOR
                ]);

            default:
                return false;
        }
    }
}
