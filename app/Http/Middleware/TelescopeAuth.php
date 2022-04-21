<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TelescopeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->session()->has('telescope_session_token')) {
            return $response;
        }

        if (!$token = $request->input('token')) {
            return response()->json([
                'message' => trans('auth.unauthenticated'),
                'error' => trans('auth.token_not_provided')
            ], 401);
        }

        $payload = JWTAuth::setToken($token)->getPayload();

        if ($payload->get('role') != "admin" || !$payload->get('verified') || $payload->get('status') != UserStatus::APPROVED) {
            throw new AccessDeniedHttpException();
        }

        $exp = $payload->get('exp');
        $minutes = floor(($exp - time()) / 60);
        if ($minutes < config('session.lifetime')) {
            config(['session.lifetime' => $minutes]);
        }

        $request->session()->put('telescope_session_token', $token);
        return $next($request);
    }
}
