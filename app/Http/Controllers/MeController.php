<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Helpers\TwilioHelper;
use App\Http\Resources\UserResource;
use App\Mail\Cellphone;
use App\Models\AccessToken;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\RestException;
use Twilio\Exceptions\TwilioException;

class MeController extends Controller
{
    /**
     * @var UserService
     */
    protected $service;

    /**
     * UserController constructor.
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the authenticated User.
     *
     * @param Request $request
     * @return UserResource
     */
    public function show(Request $request): UserResource
    {
        if (auth()->user()) {
            return new UserResource(auth()->user());
        } else {
            $token = $request->header('Authorization');
            $accessToken = AccessToken::where('token', $token)->first();
            $user = User::find($accessToken->user_id);

            return new UserResource($user);
        }
    }

    /**
     * Update the authenticated User.
     *
     * @param Request $request
     * @return JsonResponse|UserResource|null
     */
    public function update(Request $request)
    {
        $user = $this->service
            ->setRequest($request)
            ->update(auth()->user()->id);

        if (!$user) {
            return response()->json([
                'message' => trans('core::message.update_failed')
            ], 400);
        }

        return (new UserResource($user))
            ->additional(['message' => trans('core::message.updated')]);
    }

    /**
     * Update the authenticated User email.
     *
     * @param Request $request
     * @return JsonResponse|UserResource|null
     * @throws TwilioException
     * @throws CustomException|RestException
     */
    public function updateEmail(Request $request)
    {
        $user = $this->service
            ->setRequest($request)
            ->updateEmail(auth()->user());

        if (!$user) {
            return response()->json([
                'message' => trans('core::message.update_failed')
            ], 400);
        }

        return (new UserResource($user))
            ->additional(['message' => trans('core::message.updated')]);
    }

    /**
     * Update the authenticated User cellphone.
     *
     * @param Request $request
     * @return JsonResponse|UserResource|null
     * @throws ConfigurationException|TwilioException|CustomException
     */
    public function updateCellphone(Request $request)
    {
        $user = $this->service
            ->setRequest($request)
            ->updateCellphone(auth()->user());

        if (!$user) {
            return response()->json([
                'message' => trans('core::message.update_failed')
            ], 400);
        }

        Mail::to($user->email)->queue(new Cellphone);

        TwilioHelper::verify()
            ->verifications
            ->create('+' . $user->cellphone, 'sms');

        return (new UserResource($user))
            ->additional(['message' => trans('core::message.updated')]);
    }

    /**
     * Update the authenticated User password.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $response = $this->service
            ->setRequest($request)
            ->updatePassword(auth()->user());

        if (!$response) {
            return response()->json([
                'message' => trans('core::message.update_failed')
            ], 400);
        }

        return response()->json([
            'message' => trans('passwords.updated'),
        ]);
    }

    /**
     * Upload a newly created resource or update avatar image in storage.
     *
     * @param Request $request
     * @return JsonResponse|UserResource
     */
    public function uploadAvatar(Request $request)
    {
        $user = $this->service
            ->setRequest($request)
            ->uploadAvatar(auth()->user());

        if (!$user) {
            return response()->json([
                'message' => trans('message.upload_failed')
            ], 400);
        }

        return (new UserResource($user))
            ->additional(['message' => trans('message.uploaded')]);
    }

    /**
     * Remove the authenticated User.
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(): JsonResponse
    {
        $user = User::find(auth()->user()->id);
        auth()->invalidate(true);
        $user->delete();

        return response()->json(['message' => trans('message.deleted')]);
    }

    /**
     * Get the authenticated User permissions.
     *
     * @return UserResource
     */
    public function permissions(): UserResource
    {
        return new UserResource(
            User::where('id', auth()->user()->id)
                ->with('permissions')
                ->first()
        );
    }
}
