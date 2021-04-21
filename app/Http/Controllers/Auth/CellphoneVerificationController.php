<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\TwilioHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's cellphone as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function verify(Request $request)
    {

        if ($request->user()->hasVerifiedCellphone()) {
            return  $this->alreadyVerified();
        }

        $request->validate([
            'code' => 'required|numeric',
        ]);

        $verification = TwilioHelper::verify()->verificationChecks->create($request['code'], array('to' => '+' . $request->user()->cellphone));

        if (!$verification->valid) {
            return  new JsonResponse([
                'message' => trans('cellphone.verify.message'),
                'error' => trans('cellphone.verify.error')
            ], 422);
        }

        $request->user()->cellphone_verified_at = now();
        $request->user()->update();

        return $this->verified();
    }

    /**
     * The user has been verified.
     *
     * @return mixed
     */
    protected function verified()
    {
        return  new JsonResponse(['message' => trans('cellphone.verified')], 200);
    }

    /**
     * The cellphone already verified.
     *
     * @return mixed
     */
    protected function alreadyVerified()
    {
        return  new JsonResponse(['message' => trans('cellphone.already_verified')], 200);
    }

    /**
     * Resend the cellphone verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $request->validate([
            'cellphone' => 'required|numeric|exists:users,cellphone',
        ]);

        if ($request->user()->cellphone == $request['cellphone']) {
            if ($request->user()->hasVerifiedCellphone()) {
                return $this->alreadyVerified();
            }

            TwilioHelper::verify()->verifications->create('+' . $request->user()->cellphone, 'sms');

            return  new JsonResponse(['message' => trans('cellphone.sent')], 200);
        }

        return  new JsonResponse([
            'message' => trans('cellphone.send.message'),
            'error' => trans('cellphone.send.error')
        ], 422);
    }
}
