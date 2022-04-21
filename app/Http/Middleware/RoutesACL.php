<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class RoutesACL
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
        $route = explode('/', $request->route()->getPrefix());

        if (!in_array(auth()->user()->role->name, config('auth.routes_allowed_roles.' . $route[2]))) {
            throw new AccessDeniedHttpException;
        }

        return $next($request);
    }
}
