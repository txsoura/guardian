<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\TwilioHelper;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

class EmailVerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth')->only('resend');
        $this->middleware('throttle:10,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException|TwilioException
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|digits:6',
        ]);

        $user = User::whereEmail($request['email'])->first();

        if ($user->hasVerifiedEmail()) {
            return $this->alreadyVerified();
        }

        $verification = TwilioHelper::verify()
            ->verificationChecks
            ->create($request['code'], array('to' => $user->email));

        if (!$verification->valid) {
            return new JsonResponse(['message' => trans('email.invalid_verification_code')], 400);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if ($response = $this->verified()) {
            return $response;
        }

        return new JsonResponse(['message' => trans('email.verify_failed')], 400);
    }

    /**
     * The user has been verified.
     *
     * @return JsonResponse
     */
    protected function verified(): JsonResponse
    {
        return new JsonResponse(['message' => trans('email.verified')]);
    }

    /**
     * The email already verified.
     *
     * @return JsonResponse
     */
    protected function alreadyVerified(): JsonResponse
    {
        return new JsonResponse(['message' => trans('email.already_verified')]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException|TwilioException
     */
    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->alreadyVerified();
        }

        $request->user()->sendEmailVerificationNotification();

        return new JsonResponse(['message' => trans('email.sent')]);
    }
}
