<?php

namespace App\Http\Controllers;

use App\Enums\TwoFactorProvider;
use App\Http\Helpers\TwoFactorHelper;
use App\Http\Helpers\TwoFactorRecoveryHelper;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $this->middleware('jwt.auth');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Get the authenticated User two factor.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return response()->json([
            'status' => auth()->user()->two_factor_provider ? true : false,
            'provider' => auth()->user()->two_factor_provider
        ], 200);
    }

    /**
     * Update the authenticated User two factor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $request->validate([
            'provider' => ['nullable', 'string', Rule::in(TwoFactorProvider::toArray())],
        ]);

        if ($request['provider'] == TwoFactorProvider::SMS && $user->cellphone_verified_at == null) {
            return response()->json([
                'message' => trans('twoFactor.enable.message'),
                'error' => trans('twoFactor.enable.sms.error')
            ], 422);
        }

        if ($request['provider'] == TwoFactorProvider::MAIL && $user->email_verified_at == null) {
            return response()->json([
                'message' => trans('twoFactor.enable.message'),
                'message' => trans('twoFactor.enable.mail.error')
            ], 422);
        }

        if (!$request['provider']) {
            $recovery = TwoFactorRecoveryHelper::delete($user);
        } else {
            $recovery = TwoFactorRecoveryHelper::firstOrCreate($user);
        }

        $user->two_factor_provider = $request['provider'];
        $user->update();

        return response()->json([
            'status' => $user->two_factor_provider ? true : false,
            'provider' => $user->two_factor_provider,
            'recovery_code' => $recovery
        ], 200);
    }

    /**
     * Mark the authenticated user's cellphone as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        $verification = TwoFactorHelper::verify(auth()->user()->id, $request['code']);

        if (!$verification) {
            return  new JsonResponse([
                'message' => 'twoFactor.verify.message',
                'error' => 'twoFactor.verify.error'
            ], 422);
        }

        return  new JsonResponse([
            'message' => 'twoFactor.verified'
        ], 200);
    }

    /**
     * Send two factor verification notification.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function send()
    {
        return TwoFactorHelper::send(auth()->user()->id);
    }
}
