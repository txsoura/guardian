<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection(
            Role::orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RoleResource
     */
    public function store(Request $request): RoleResource
    {
        $request['name'] = Str::lower($request['name']);

        $request->validate([
            'name' => 'required|string|unique:acl_roles,name',
            'description' => 'required|string'
        ]);

        $role = Role::create($request->all());

        return new RoleResource($role, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role): RoleResource
    {
        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Role $role
     * @return RoleResource
     */
    public function update(Request $request, Role $role): RoleResource
    {
        if ($request['name'])
            $request['name'] = Str::lower($request['name']);

        $request->validate([
            'name' => 'sometimes|required|string|unique:acl_roles,name',
            'description' => 'sometimes|required|string'
        ]);

        $role->update($request->all());

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return response()->json(['message' => trans('message.deleted')]);
    }
}
