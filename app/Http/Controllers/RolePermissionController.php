<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolePermissionResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Role $role)
    {
        return RolePermissionResource::collection(
            RolePermission::where('acl_role_id', $role->id)->get(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Role $role)
    {

        $request->validate([
            'acl_permission_id' => 'required|numeric|exists:acl_permissions,id'
        ]);

        $request['acl_role_id'] = $role->id;

        $rolePermission = RolePermission::create($request->all());

        return new RolePermissionResource($rolePermission, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     * @param  Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role, Permission $permission)
    {
        RolePermission::where('acl_role_id', $role->id)
            ->where('acl_permission_id', $permission->id)
            ->delete();

        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
