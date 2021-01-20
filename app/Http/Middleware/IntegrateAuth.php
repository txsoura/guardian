<?php

namespace App\Http\Middleware;

use Closure;

class IntegrateAuth
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
        $app = $request->route('app');
        $token = $request->route('token');

        if (!config('services.' . $app)) {
            return response()->json([
                'message' => trans('message.not_found'),
                'error' => trans('message.app_not_found')
            ], 422);
        }

        if (config('services.' . $app . '.key') != $token) {
            return response()->json([
                'message' => trans('auth.unauthenticated'),
                'error' => trans('auth.token_invalid')
            ], 401);
        }

        return $next($request);
    }
}
