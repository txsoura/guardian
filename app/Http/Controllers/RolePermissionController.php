<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolePermissionResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Role $role): AnonymousResourceCollection
    {
        return RolePermissionResource::collection(
            RolePermission::where('acl_role_id', $role->id)->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RolePermissionResource
     */
    public function store(Request $request, Role $role): RolePermissionResource
    {
        $request->validate([
            'acl_permission_id' => [
                'required', 'numeric', 'exists:acl_permissions,id',
                Rule::unique('acl_role_permissions', 'acl_permission_id', 'acl_role_id')
            ]
        ]);

        $request['acl_role_id'] = $role->id;

        $rolePermission = RolePermission::create($request->all());

        return new RolePermissionResource($rolePermission, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @param Permission $permission
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Role $role, Permission $permission): JsonResponse
    {
        RolePermission::where('acl_role_id', $role->id)
            ->where('acl_permission_id', $permission->id)
            ->delete();

        return response()->json(['message' => trans('message.deleted')]);
    }
}
