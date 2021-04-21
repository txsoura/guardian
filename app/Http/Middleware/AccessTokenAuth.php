<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;

class AccessTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');

        $accessToken = AccessToken::where('token', $token)->first();

        if (!$accessToken) {
            return response()->json([
                'message' => trans('auth.unauthenticated'),
                'error' => trans('auth.invalid_token')
            ], 401);
        }

        if ($accessToken->expiration < today()) {
            return response()->json([
                'message' => trans('auth.unauthenticated'),
                'error' => trans('auth.token_expired')
            ], 401);
        }

        $accessToken->last_used_at=now();
        $accessToken->update();

        return $next($request);
    }
}
