<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\RolePermission;
use Closure;

class AuthACL
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
        if (auth()->user() && $request->route()->getName()) {

            if (!auth()->user()->role->name) {
                return response()->json([
                    'message' => trans('message.role_not_found'),
                    'error' => trans('auth.permission_denied')
                ], 403);
            }

            $role = auth()->user()->role->id;

            $route = $request->route()->getName();
            $permission = Permission::where('name', $route)->first();

            if (!$permission) {
                return response()->json([
                    'message' => trans('message.permission_not_found'),
                    'error' => trans('auth.permission_denied')
                ], 403);
            }

            $rolePermission = RolePermission::where('acl_role_id', $role)->where('acl_permission_id', $permission->id)->first();

            if (!$rolePermission) {
                return response()->json([
                    'message' => trans('message.no_permission'),
                    'error' => trans('auth.permission_denied')
                ], 403);
            }
        }

        return $next($request);
    }
}
