<?php

namespace App\Http\Middleware;

use App\Models\AccessToken;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AccessTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws TokenExpiredException|TokenInvalidException
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        $accessToken = AccessToken::where('token', $token)->first();

        if (!$accessToken) {
            throw new TokenInvalidException;
        }

        if ($accessToken->expiration && $accessToken->expiration < today()) {
            throw new TokenExpiredException;
        }

        $accessToken->last_used_at = now();
        $accessToken->update();

        return $next($request);
    }
}
