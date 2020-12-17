<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
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
        //
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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.entry_not_found', ['model' => str_replace('App\\Models\\', '', $exception->getModel())])
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
                TokenInvalidException
            ) {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.token_invalid')
                ], 401);
            } else if ($preException instanceof
                TokenBlacklistedException
            ) {
                return response()->json([
                    'message' => trans('auth.unauthenticated'),
                    'error' => trans('auth.token_blacklisted')
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
                'error' => trans('auth.already_logged_out')
            ], 422);
        }

        if ($exception instanceof
            UnauthorizedHttpException
        ) {
            return response()->json([
                'message' => trans('auth.not_found'),
                'error' => trans('auth.user_not_found')
            ], 404);
        }
        if ($exception->getMessage() === 'Pendent or blocked user cannot login') {
            return response()->json([
                'message' => trans('message.no_access'),
                'error' => trans('auth.pendent_or_blocked')
            ], 403);
        }

        return parent::render($request, $exception);
    }
}
