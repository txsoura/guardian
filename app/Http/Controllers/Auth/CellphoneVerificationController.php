<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\TwilioHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

class CellphoneVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Cellphone Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling sms verification for any
    | user that recently registered his cellphone. SMSs may also
    | be re-sent if the user didn't receive the original sms message.
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
        $this->middleware('throttle:10,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's cellphone as verified.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws ConfigurationException|TwilioException
     */
    public function verify(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedCellphone()) {
            return $this->alreadyVerified();
        }

        $request->validate([
            'code' => 'required|string|digits:6',
        ]);

        $verification = TwilioHelper::verify()
            ->verificationChecks
            ->create($request['code'], array('to' => '+' . $request->user()->cellphone));

        if (!$verification->valid) {
            return new JsonResponse([
                'message' => trans('cellphone.verify.message'),
                'error' => trans('cellphone.verify.error')
            ], 400);
        }

        $request->user()->cellphone_verified_at = now();
        $request->user()->update();

        return $this->verified();
    }

    /**
     * The user has been verified.
     *
     * @return JsonResponse
     */
    protected function verified(): JsonResponse
    {
        return new JsonResponse(['message' => trans('cellphone.verified')]);
    }

    /**
     * The cellphone already verified.
     *
     * @return JsonResponse
     */
    protected function alreadyVerified(): JsonResponse
    {
        return new JsonResponse(['message' => trans('cellphone.already_verified')]);
    }

    /**
     * Resend the cellphone verification notification.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException|TwilioException
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'cellphone' => 'required|numeric|exists:users,cellphone',
        ]);

        if ($request->user()->cellphone == $request['cellphone']) {
            if ($request->user()->hasVerifiedCellphone()) {
                return $this->alreadyVerified();
            }

            TwilioHelper::verify()
                ->verifications
                ->create('+' . $request->user()->cellphone, 'sms');

            return new JsonResponse(['message' => trans('cellphone.sent')]);
        }

        return new JsonResponse([
            'message' => trans('cellphone.send.message'),
            'error' => trans('cellphone.send.error')
        ], 400);
    }
}
