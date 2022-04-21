<?php

namespace App\Http\Middleware;

use App\Enums\UserStatus;
use Closure;
use Illuminate\Http\Request;

class AuthStatusCheck
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

        // Check if current authenticated user status can't access the system
        if (auth()->user()->status == UserStatus::BLOCKED || (!config('auth.pendent_user') && auth()->user()->status == UserStatus::PENDENT)) {
            auth()->invalidate(true);
        }

        if (auth()->user()->role_id != config('auth.default_role') && auth()->user()->status == UserStatus::PENDENT) {
            auth()->invalidate(true);

            return response()->json([
                'message' => trans('auth.unauthenticated'),
                'error' => trans('auth.user_pendent_or_blocked')
            ], 401);
        }

        return $next($request);
    }
}
