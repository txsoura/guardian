<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        return User::create([
            'role_id' => config('auth.default_role'),
            'status' => UserStatus::PENDENT,
            'name' => ucwords($data['name']),
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $request['email'] = Str::lower($request['email']);
        $this->validator($request->all())->validate();

        $this->create($request->all());

        if ($response = $this->registered()) {
            return $response;
        }

        return new JsonResponse(['message' => trans('auth.register_failed')], 400);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return Guard|StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @return JsonResponse
     */
    protected function registered(): JsonResponse
    {
        return response()->json(['message' => trans('auth.user_registered')], 201);
    }
}
