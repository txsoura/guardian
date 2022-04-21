<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ActivityLog;
use App\Http\Helpers\TwoFactorHelper;
use App\Http\Helpers\TwoFactorRecoveryHelper;
use App\Http\Resources\UserResource;
use App\Mail\Login;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use ThrottlesLogins;

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException|TwilioException|ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            if ($checker = $this->userCheck($request)) {
                return $checker;
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse();
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     *
     */
    protected function validateLogin(Request $request)
    {
        $request['email'] = Str::lower($request['email']);
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'email';
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request): bool
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard|StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return $request->only($this->username(), 'password', 'status');
    }

    /**
     * Check extra user data before authentication, such as status and two-factor enabled.
     *
     * @param Request $request
     * @return false|JsonResponse
     * @throws ConfigurationException|TwilioException
     */
    protected function userCheck(Request $request)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user->status == UserStatus::BLOCKED) {
            return $this->noAccess('blocked');
        }

        if (!config('auth.pendent_user') && $user->status == UserStatus::PENDENT) {
            return $this->noAccess('pendent');
        }

        if (($user->status == UserStatus::APPROVED || (config('auth.pendent_user') && $user->status == UserStatus::PENDENT)) && $user->two_factor_provider != null) {
            return TwoFactorHelper::send($user->id);
        }

        return 0;
    }


    /**
     * The user has no access to the application.
     *
     * @param $text
     * @return JsonResponse
     */
    protected function noAccess($text): JsonResponse
    {
        return new JsonResponse([
            'message' => trans('message.no_access'),
            'error' => $text == 'blocked' ? trans('auth.user_blocked') : trans('auth.user_pendent')
        ], 403);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function sendLoginResponse(Request $request): JsonResponse
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request)) {
            return $response;
        }

        return new JsonResponse(['message' => trans('auth.login_failed')], 400);
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function authenticated(Request $request): JsonResponse
    {
        $this->sendLoginAttempEmailNotification($request->email, $request->ip());

        $response = $this->respondWithToken(auth()->attempt($this->credentials($request)));

        ActivityLog::createActivityLog('auth_login', 'auth_login_description', "users", auth()->user()->id, $request);

        return $response;
    }

    private function sendLoginAttempEmailNotification($email, $ip)
    {
        $data = ['ip' => $ip, 'date' => now() . " (UTC)"];

        Mail::to($email)->queue(new Login($data));
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @return JsonResponse
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return new JsonResponse([
            'message' => trans('auth.user_logged_in'),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => new UserResource(auth()->user()),
        ]);
    }

    /**
     * Get the failed login response instance.
     *
     * @return Response
     * @throws ValidationException
     */
    protected function sendFailedLoginResponse(): Response
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        ActivityLog::createActivityLog('auth_logout', 'auth_logout_description', "users", auth()->user()->id, $request);

        $this->guard()->logout();

        if ($response = $this->loggedOut()) {

            return $response;
        }

        return new JsonResponse(['message' => trans('auth.logout_failed')], 400);
    }

    /**
     * The user has logged out of the application.
     *
     * @return JsonResponse
     */
    protected function loggedOut(): JsonResponse
    {
        return new JsonResponse(['message' => trans('auth.user_logged_out')]);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        ActivityLog::createActivityLog('auth_refresh', 'auth_refresh_description', "users", auth()->user()->id, request());

        return $this->respondWithToken(auth()->refresh(true, true));
    }

    public function redirectToProvider($provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Resend login two factor code.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws TwilioException|ConfigurationException
     *
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request['email'])
            ->where('status', '!=', UserStatus::BLOCKED)
            ->where('two_factor_provider', '!=', null)
            ->firstOrFail();

        if ($user->status == UserStatus::APPROVED || (config('auth.pendent_user') && $user->status == UserStatus::PENDENT)) {
            return TwoFactorHelper::send($user->id);
        }

        return new JsonResponse(['message' => trans('twoFactor.two_factor_resend_failed')], 400);
    }

    /**
     * Confirm login two factor code.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ConfigurationException|TwilioException
     */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string|digits:6',
        ]);

        $user = User::where('email', $request['email'])
            ->where('status', '!=', UserStatus::BLOCKED)
            ->where('two_factor_provider', '!=', null)
            ->firstOrFail();

        $verification = TwoFactorHelper::verify($user->id, $request['code']);

        if (!$verification) {
            return new JsonResponse([
                'message' => trans('twoFactor.verify.message'),
                'error' => trans('twoFactor.verify.error')
            ], 400);
        }

        if ($token = auth()->login($user, true)) {
            $this->sendLoginAttempEmailNotification($request->email, $request->ip());

            $response = $this->respondWithToken($token);

            ActivityLog::createActivityLog('auth_two_factor_confirm', 'auth_two_factor_confirm_description', "users", auth()->user()->id, $request);

            return $response;
        }

        return new JsonResponse(['message' => trans('auth.login_confirm_failed')], 400);
    }

    /**
     * Recovery user two factor.
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function recovery(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string',
        ]);

        $user = User::where('email', $request['email'])
            ->where('status', '!=', UserStatus::BLOCKED)
            ->where('two_factor_provider', '!=', null)
            ->firstOrFail();

        $verification = TwoFactorRecoveryHelper::recovery($user, $request['code']);

        if (!$verification) {
            return new JsonResponse([
                'message' => trans('twoFactor.recovery.message'),
                'error' => trans('twoFactor.recovery.error')
            ], 400);
        }

        if ($token = auth()->login($user, true)) {

            $this->sendLoginAttempEmailNotification($request->email, $request->ip());

            $response = $this->respondWithToken($token);

            ActivityLog::createActivityLog('auth_two_factor_recovery', 'auth_two_factor_recovery_description', "users", auth()->user()->id, $request);

            return $response;
        }

        return new JsonResponse(['message' => trans('twoFactor.two_factor_recovery_failed')], 400);
    }

    /**
     * @throws TwilioException|ConfigurationException
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $email = Str::lower($user->email);

        $validUser = User::where('email', $email)->first();

        if (!$validUser) {
            $validUser = User::create([
                'avatar' => $user->avatar,
                'name' => ucwords($user->name),
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Str::random(18),
                'role_id' => config('auth.default_role')
            ]);
        }

        App::setLocale($validUser->lang);

        $request['email'] = $email;

        if ($checker = $this->userCheck($request)) {
            return $checker;
        }

        if ($token = auth()->login($validUser, true)) {

            $this->sendLoginAttempEmailNotification($request->email, $request->ip());

            $response = $this->respondWithToken($token);

            ActivityLog::createActivityLog('auth_social_login', 'auth_social_login_description', "users", auth()->user()->id, $request);

            return $response;
        }

        return new JsonResponse(['message' => trans('auth.social_login_failed')], 400);
    }
}
