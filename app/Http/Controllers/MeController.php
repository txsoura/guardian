<?php

namespace App\Http\Controllers;

use App\Http\Helpers\TwilioHelper;
use App\Http\Resources\UserResource;
use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MeController extends Controller
{
    /**
     * Get the authenticated User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        if (auth()->user()) {
            return new UserResource(auth()->user(), 200);
        } else {
            $token = $request->header('Authorization');
            $accessToken = AccessToken::where('token', $token)->first();
            $user = User::find($accessToken->user_id);

            return new UserResource($user, 200);
        }
    }

    /**
     * Update the authenticated User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $user = User::find(auth()->user()->id);

        $user->name = ucwords($request['name']);
        $user->update();

        return new UserResource($user, 200);
    }

    /**
     * Update the authenticated User email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {
        $request['email'] = Str::lower($request['email']);

        $request->validate([
            'email' => 'required|string|email|unique:users',
        ]);

        $user = User::find(auth()->user()->id);

        $user->email = $request['email'];
        $user->email_verified_at = null;
        $user->update();

        if ($user->email) {
            $user->sendEmailVerificationNotification();
        }

        return new UserResource($user, 200);
    }

    /**
     * Update the authenticated User cellphone.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCellphone(Request $request)
    {
        $request->validate([
            'cellphone' => 'nullable|numeric|unique:users',
        ]);

        $user = User::find(auth()->user()->id);

        $user->cellphone = $request['cellphone'];
        $user->cellphone_verified_at = null;
        $user->update();

        if ($user->cellphone) {
            TwilioHelper::verify()->verifications->create('+' . $user->cellphone, 'sms');
        }

        return new UserResource($user, 200);
    }

    /**
     * Update the authenticated User password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|different:current_password|confirmed',
        ]);

        $user = User::find(auth()->user()->id);

        if (password_verify($request['current_password'], $user->password)) {
            $user->password = Hash::make($request['password']);
            $user->update();

            return response()->json([
                'message' => trans('passwords.updated'),
            ], 200);
        }

        return response()->json([
            'message' => trans('passwords.update.message'),
            'error' => trans('passwords.update.error')
        ], 422);
    }

    /**
     * Upload a newly created resource or update avatar image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|max:2000|image',
        ]);

        $user = User::find(auth()->user()->id);

        $disk = Storage::disk('public');

        if ($user->avatar) {
            $disk->delete($user->avatar);
        }

        $folders = array_merge(['users', 'avatars'], str_split($user->id));
        $dir = implode('/', $folders);
        $name = time() . '.' . $request->avatar->getClientOriginalExtension();

        if (!$disk->has($dir)) {
            $disk->makeDirectory($dir);
        }

        $path = $disk->putFileAs($dir, $request->avatar, $name);

        $user->avatar = $path;
        $user->update();

        return new UserResource($user, 201);
    }

    /**
     * Remove the authenticated User.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = User::find(auth()->user()->id);
        auth()->invalidate(true);
        $user->delete();

        return response()->json(['message' => trans('message.deleted')], 200);
    }

    /**
     * Get the authenticated User permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions()
    {
        return new UserResource(User::where('id',auth()->user()->id)->with('permissions')->first(), 200);
    }
}
