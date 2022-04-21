<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IntegrateAuth
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
        $app = $request->route('app');
        $token = $request->route('token');

        if (!config('services.' . $app)) {
            return response()->json(['status' => 'APP_NOT_FOUND'], 404);
        }

        if (config('services.' . $app . '.key') != $token) {
            return response()->json(['status' => 'UNAUTHORIZED'], 401);
        }

        return $next($request);
    }
}
