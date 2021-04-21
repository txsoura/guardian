<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use Closure;

class AuthStatusCheck
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
        // Check if user is authenticated
        if (auth()->user()) {

            // Check if current authenticated user status can't access the system
            if (auth()->user()->status == UserStatus::BLOCKED || (!config('auth.pendent_user') && auth()->user()->status == UserStatus::PENDENT)) {
                auth()->invalidate(true);
            }
        }

        return $next($request);
    }
}
