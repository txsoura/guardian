<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Events\UserCreated;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return UserResource::collection(
            User::when($request['include'], function ($query, $include) {
                return $query->with(explode(',',  $include));
            })
                ->orderBy('created_at', 'desc')
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
        $request['email'] = Str::lower($request['email']);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|numeric|exists:acl_roles,id'
        ]);

        $password = Str::random(8);

        $user = User::create([
            'name' => ucwords($request['name']),
            'email' => $request['email'],
            'role_id' => $request['role_id'],
            'password' => Hash::make($password),
            'status' => UserStatus::APPROVED
        ]);

        event(new UserCreated($user, $password));
        event(new Registered($user));

        return new UserResource($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        return new UserResource(User::where('id', $user->id)
            ->when($request['include'], function ($query, $include) {
                return $query->with(explode(',',  $include));
            })
            ->firstOrFail(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'numeric|exists:acl_roles,id',
        ]);

        $user->update($request->only('role_id'));

        return new UserResource($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => trans('message.deleted')], 200);
    }

    /**
     * Update the status to approved.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function approve(User $user)
    {
        $user->status = UserStatus::APPROVED;
        $user->update();

        return new UserResource($user, 200);
    }

    /**
     * Update the status to blocked.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function block(User $user)
    {
        $user->status = UserStatus::BLOCKED;
        $user->update();

        return new UserResource($user, 200);
    }
}
