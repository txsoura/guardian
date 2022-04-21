<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Twilio\Exceptions\RestException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        CustomException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception): Response
    {
        if ($exception instanceof RouteNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.route_not_found')
            ], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 405);
        }

        if ($exception instanceof ThrottleRequestsException && $request->wantsJson()) {
            return response()->json([
                'error' => trans('message.too_many_requests')
            ], 429);
        }

        if ($exception instanceof InvalidSignatureException && $request->wantsJson()) {
            return response()->json([
                'error' => trans('message.invalid_signature')
            ], 403);
        }

        if ($exception instanceof AccessDeniedHttpException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.no_permission'),
                'error' => trans('auth.access_denied')
            ], 403);
        }

        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.entry_not_found', ['model' => str_replace('App\\Models\\', '', $exception->getModel())])
            ], 404);
        }

        if ($exception instanceof RelationNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.relation_not_found')
            ], 404);
        }

        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.route_not_found')
            ], 404);
        }

        if ($exception instanceof UnauthorizedHttpException) {

            $preException = $exception->getPrevious();

            if ($preException instanceof
                TokenExpiredException
            ) {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.token_expired')
                ], 401);

            } else if ($preException instanceof
                TokenBlacklistedException
            ) {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.token_blacklisted')
                ], 401);


            } else if ($preException instanceof
                TokenInvalidException
            ) {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.token_invalid')
                ], 401);
            } else if ($preException instanceof
                JWTException
            ) {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.token_cannot_parse')
                ], 401);
            }

            if ($exception->getMessage() === 'Token not provided') {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.token_not_provided')
                ], 401);
            }

            if ($exception->getMessage() === 'User not found') {
                return response()->json([
                    'message' => trans('message.not_found'),
                    'error' => trans('auth.user_not_found')
                ], 404);
            }

            //To log untreated unauthorized exceptions
            Log::error('UNAUTHORIZED_EXCEPTION:' . $exception->getMessage());
            return response()->json([
                'message' => trans('auth.unauthenticated'),
                'error' => trans('message.contact_support')
            ], 401);
        }

        if ($exception instanceof
            JWTException
        ) {
            return response()->json([
                'message' => trans('auth.unauthenticated'),
                'error' => trans('auth.user_already_logged_out')
            ], 401);
        }

        if ($exception instanceof
            HttpException
        ) {
            if ($exception->getMessage() === 'Your email address is not verified.') {
                return response()->json([
                    'message' => trans('message.no_permission'),
                    'error' => trans('auth.verify_email')
                ], 403);
            }

            return response()->json([
                'message' => trans('message.no_permission'),
            ], 403);
        }

        if ($exception instanceof RestException && $request->wantsJson()) {
            Log::error('TWILIO_VERIFY:' . $exception->getMessage());

            return response()->json([
                'error' => trans('twoFactor.verify.message')
            ], 400);
        }

        return parent::render($request, $exception);
    }
}
