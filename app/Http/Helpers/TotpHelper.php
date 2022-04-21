<?php

namespace App\Http\Helpers;

use App\Models\User;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FALaravel\Google2FA as Google2FALaravel;

class TotpHelper
{
    /**
     * generate totp secret
     * @throws IncompatibleWithGoogleAuthenticatorException|SecretKeyTooShortException|InvalidCharactersException
     */
    public static function generateTotpSecret(User $user): string
    {
        $google2fa = new Google2FA();

        $prefix = Str::padLeft("usr-$user->id", 30, '0');

        return $google2fa->generateSecretKey(16, $prefix);
    }

    /**
     * generate totp QR code url
     */
    public static function generateTotpQRCodeUrl(User $user, $secret): string
    {
        $google2fa = new Google2FA();

        return $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
    }

    /**
     * verify if secret & code match
     */
    public static function verify($secret, $code): bool
    {
        $request = Request();
        $google2faLaravel = new Google2FALaravel($request);

        $valid = $google2faLaravel->verifyGoogle2FA($secret, $code);

        if (!$valid) {
            return false;
        }

        return true;
    }
}
