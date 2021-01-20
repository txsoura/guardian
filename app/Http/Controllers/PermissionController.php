<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PermissionResource::collection(
            Permission::orderBy('created_at', 'desc')
                ->get(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['name'] = Str::lower($request['name']);
        $request['model'] = Str::lower($request['model']);

        $request->validate([
            'name' => 'required|string|unique:acl_permissions',
            'model' => 'required|string',
            'description' => 'required|string'
        ]);


        $permission = Permission::create($request->all());

        return new PermissionResource($permission, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return new PermissionResource(
            $permission,
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $request['name'] = Str::lower($request['name']);
        $request['model'] = Str::lower($request['model']);

        $request->validate([
            'name' => 'string|unique:acl_permissions',
            'model' => 'string',
            'description' => 'string'
        ]);


        $permission->update($request->all());

        return new PermissionResource($permission, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
