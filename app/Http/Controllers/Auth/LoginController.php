<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Helpers\TwoFactorHelper;
use App\Http\Helpers\TwoFactorRecoveryHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
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

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
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
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'status');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return new Response('', 204);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request)
    {
        return $this->respondWithToken(auth()->attempt($this->credentials($request)));
    }

    /**
     * Check extra user data before authentication, such as status and two-factor enabled.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
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
    }


    /**
     * The user has no access to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function noAccess($text)
    {
        return response()->json([
            'message' =>  trans('message.no_access'),
            'error' => $text == 'blocked' ? trans('auth.user_blocked') : trans('auth.user_pendent')
        ], 422);
    }

    /**
     * Get the failed login response instance.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse()
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout(true);

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        if ($request->wantsJson()) {
            return new Response('', 204);
        }
    }

    /**
     * The user has logged out of the application.
     *
     * @return mixed
     */
    protected function loggedOut()
    {
        return response()->json(['message' => trans('auth.user_logged_out')], 200);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(true,true));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithToken($token)
    {
        return response()->json([
            'message' =>  trans('auth.user_logged_in'),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Resend login two factor code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request['email'])->where('status', '!=', UserStatus::BLOCKED)->where('two_factor_provider', '!=', null)->firstOrFail();

        if ($user->status == UserStatus::APPROVED || (config('auth.pendent_user') && $user->status == UserStatus::PENDENT)) {
            return TwoFactorHelper::send($user->id);
        }
    }

    /**
     * Confirm login two factor code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string',
        ]);

        $user = User::where('email', $request['email'])->where('status', '!=', UserStatus::BLOCKED)->where('two_factor_provider', '!=', null)->firstOrFail();

        $verification = TwoFactorHelper::verify($user->id, $request['code']);

        if (!$verification) {
            return  new JsonResponse([
                'message' => trans('twoFactor.verify.message'),
                'error' => trans('twoFactor.verify.error')
            ], 422);
        }

        if ($token = auth()->login($user, true)) {
            return $this->respondWithToken($token);
        }
    }

    /**
     * Recovery user two factor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function recovery(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'code' => 'required|string',
        ]);

        $user = User::where('email', $request['email'])->where('status', '!=', UserStatus::BLOCKED)->where('two_factor_provider', '!=', null)->firstOrFail();

        $verification = TwoFactorRecoveryHelper::recovery($user, $request['code']);

        if (!$verification) {
            return  new JsonResponse([
                'message' => trans('twoFactor.recovery.message'),
                'error' => trans('twoFactor.recovery.error')
            ], 422);
        }

        if ($token = auth()->login($user, true)) {
            return $this->respondWithToken($token);
        }
    }

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
                'password' => Hash::make(Str::random(18)),
                'role_id' => config('auth.default_role')
            ]);
        }

        $request['email'] = $email;

        if ($checker = $this->userCheck($request)) {
            return $checker;
        }

        if ($token = auth()->login($validUser, true)) {

            return $this->respondWithToken($token);
        }
    }
}
