<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    // public function create()
    // {
    //     return view('auth.register');
    // }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        Log::info($request->all());
        Log::info('2');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'role' => 'required|numeric|exists:roles,id'
        ]);

        Log::info('3');
        // Auth::login($user = User::create([
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        Log::info('4');

        event(new Registered($user));
        Log::info('5');

        $this->created($user);
        // return redirect(RouteServiceProvider::HOME);
    }

    /**
     * The user has been registered.
     *
     * @param  mixed  $user
     * @return mixed
     */
    protected function created($user)
    {
        Log::info('6');
        return response()->json(['message' =>  trans('auth.registered'), 'user' => new UserResource($user)], 201);
    }
}
