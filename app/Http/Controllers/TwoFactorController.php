<?php

namespace App\Http\Controllers;

use App\Enums\TwoFactorProvider;
use App\Http\Helpers\TotpHelper;
use App\Http\Helpers\TwoFactorHelper;
use App\Http\Helpers\TwoFactorRecoveryHelper;
use App\Mail\TwoFactor;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Log;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\RestException;
use Twilio\Exceptions\TwilioException;

class TwoFactorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Two Factor Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling two factor verification for any
    | user that recently logged in or want to make a request. The code may also
    | be re-sent if the user didn't receive the original message.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['jwt.auth', 'auth.status']);
        $this->middleware('throttle:10,1')->only('verify', 'resend');
    }

    /**
     * Get the authenticated User two factor.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        return response()->json([
            'status' => (bool)auth()->user()->two_factor_provider,
            'provider' => auth()->user()->two_factor_provider
        ]);
    }

    /**
     * Update the authenticated User two factor.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException|RestException|TwilioException
     * @throws IncompatibleWithGoogleAuthenticatorException|InvalidCharactersException|SecretKeyTooShortException
     */
    public function update(Request $request): JsonResponse
    {
        $user = User::find(auth()->user()->id);

        $request->validate([
            'provider' => ['nullable', 'string', Rule::in(TwoFactorProvider::toArray())],
            'code' => 'required|string|digits:6'
        ]);

        if ($request['provider'] == TwoFactorProvider::SMS && $user->cellphone_verified_at == null) {
            return response()->json([
                'message' => trans('twoFactor.enable.message'),
                'error' => trans('twoFactor.enable.sms.error')
            ], 400);
        }

        if ($request['provider'] == TwoFactorProvider::MAIL && $user->email_verified_at == null) {
            return response()->json([
                'message' => trans('twoFactor.enable.message'),
                'error' => trans('twoFactor.enable.mail.error')
            ], 400);
        }

        $verification = TwoFactorHelper::verify($user->id, $request['code'], 'mail');

        if (!$verification) {
            throw new RestException(trans('twoFactor.verify.error'));
        }

        if (!$request['provider']) {
            TwoFactorRecoveryHelper::delete($user);
            $recovery = '';
        } else {
            $recovery = TwoFactorRecoveryHelper::firstOrCreate($user);
        }

        if ($request['provider'] == TwoFactorProvider::GOOGLE_AUTHENTICATOR) {
            $secret = TotpHelper::generateTotpSecret($user);
            $QRCodeUrl = TotpHelper::generateTotpQRCodeUrl($user, $secret);

            return response()->json([
                'status' => false,
                'provider' => TwoFactorProvider::GOOGLE_AUTHENTICATOR,
                'recovery_code' => $recovery,
                'secret' => $secret,
                'qr_code_url' => $QRCodeUrl,
            ]);
        } else {
            $user->two_factor_provider = $request['provider'];
            $user->update();

            Mail::to($user->email)->queue(new TwoFactor($user->two_factor_provider));

            return response()->json([
                'status' => (bool)$user->two_factor_provider,
                'provider' => $user->two_factor_provider,
                'recovery_code' => $recovery,
            ]);
        }
    }

    /**
     * Update the authenticated User two factor to totp.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function totpActivate(Request $request): JsonResponse
    {
        $user = User::find(auth()->user()->id);

        $request->validate([
            'secret' => 'required|string|unique:users,totp_secret',
            'code' => 'required|string|digits:6'
        ]);

        $verification = TotpHelper::verify($request['secret'], $request['code']);

        if (!$verification) {
            return response()->json([
                'message' => trans('totp.activate.message'),
                'error' => trans('totp.activate.error'),
            ], 400);
        }

        $user->two_factor_provider = TwoFactorProvider::GOOGLE_AUTHENTICATOR;
        $user->setTotpSecret($request['secret']);
        $user->update();

        Log::info($user->getTotpSecret());

        Mail::to($user->email)->queue(new TwoFactor($user->two_factor_provider));

        return response()->json([
            'message' => trans('totp.activated'),
        ]);
    }


    /**
     * Mark the authenticated user's cellphone as verified.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException|TwilioException
     */
    public function verify(Request $request): JsonResponse
    {
        $verification = TwoFactorHelper::verify(auth()->user()->id, $request['code']);

        if (!$verification) {
            return new JsonResponse([
                'message' => 'twoFactor.verify.message',
                'error' => 'twoFactor.verify.error'
            ], 400);
        }

        return new JsonResponse([
            'message' => 'twoFactor.verified'
        ]);
    }

    /**
     * Send two factor verification notification.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException
     * @throws TwilioException
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'provider' => ['sometimes', 'required', 'string', Rule::in(TwoFactorProvider::toArray())]
        ]);

        $provider = $request['provider'];

        return TwoFactorHelper::send(auth()->user()->id, $provider ?: null);
    }
}
