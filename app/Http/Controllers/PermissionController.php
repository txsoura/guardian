<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return PermissionResource::collection(
            Permission::orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return PermissionResource
     */
    public function store(Request $request): PermissionResource
    {
        $request['name'] = Str::lower($request['name']);
        $request['model'] = Str::lower($request['model']);

        $request->validate([
            'name' => 'required|string|unique:acl_permissions,name',
            'model' => 'required|string',
            'description' => 'required|string'
        ]);

        $permission = Permission::create($request->all());

        return new PermissionResource($permission, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Permission $permission
     * @return PermissionResource
     */
    public function show(Permission $permission): PermissionResource
    {
        return new PermissionResource($permission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Permission $permission
     * @return PermissionResource
     */
    public function update(Request $request, Permission $permission): PermissionResource
    {
        if ($request['name'])
            $request['name'] = Str::lower($request['name']);
        if ($request['model'])
            $request['model'] = Str::lower($request['model']);

        $request->validate([
            'name' => 'sometimes|required|string|unique:acl_permissions,name',
            'model' => 'sometimes|required|string',
            'description' => 'sometimes|required|string'
        ]);

        $permission->update($request->all());

        return new PermissionResource($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json(['message' => trans('message.deleted')]);
    }
}
