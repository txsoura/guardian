<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\RolePermission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthACL
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
        if (!auth()->user()->role->name) {
            return response()->json([
                'message' => trans('message.role_not_found'),
                'error' => trans('auth.access_denied')
            ], 403);
        }

        $role = auth()->user()->role->id;

        $route = $request->route()->getName();
        $permission = Permission::where('name', $route)->first();

        if (!$permission) {
            return response()->json([
                'message' => trans('message.permission_not_found'),
                'error' => trans('auth.access_denied')
            ], 403);
        }

        $rolePermission = RolePermission::where('acl_role_id', $role)
            ->where('acl_permission_id', $permission->id)
            ->first();

        if (!$rolePermission) {
            throw new AccessDeniedHttpException;
        }

        return $next($request);
    }
}
