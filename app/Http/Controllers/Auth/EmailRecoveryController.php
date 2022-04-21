<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class EmailRecoveryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Recovery Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email recovery for any
    | user that recently changed email with the application. This is an extra security
    | resource, so Email may also be sent one time on email update.
    |
    */

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->middleware('signed')->only('recovery');
        $this->middleware('throttle:3,1')->only('recovery');

        $this->repository = $repository;
    }

    /**
     * Recover the emailed user account.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function recover(Request $request): JsonResponse
    {
        if (!$request->hasValidSignature()) {
            throw new RouteNotFoundException;
        }

        $user = User::findOrFail($request->route('id'));

        if (!hash_equals((string)$request->route('hash'), sha1($request->input('email')))) {
            throw new AuthorizationException;
        }

        if ($user->email == $request->input('email')) {
            return $this->alreadyRecovered();
        }

        if ($this->repository->email($user, $request->input('email'))) {
            return $this->recovered();
        }

        return new JsonResponse(['message' => trans('email.recovery_failed')], 400);
    }

    /**
     * The email already recovered.
     *
     * @return JsonResponse
     */
    protected function alreadyRecovered(): JsonResponse
    {
        return new JsonResponse(['message' => trans('email.already_recovered')]);
    }

    /**
     * The user has been recovered.
     *
     * @return JsonResponse
     */
    protected function recovered(): JsonResponse
    {
        return new JsonResponse(['message' => trans('email.recovered')]);
    }
}
