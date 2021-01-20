<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccessTokenResource;
use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccessTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return AccessTokenResource::collection(AccessToken::where('user_id', $user->id)->orderBy('last_used_at', 'desc')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string',
            'expiration' => 'required|date',
            'abilities' => 'required',
        ]);

        $request['user_id'] = $user->id;
        $request['token'] = Hash::make(Str::random(13));

        $accessToken = AccessToken::create($request->all());
        return new AccessTokenResource($accessToken, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessToken  $accessToken
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, AccessToken $accessToken)
    {
        return new AccessTokenResource(AccessToken::where('id', $accessToken->id)->where('user_id', $user->id)->firstOrFail(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccessToken  $accessToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, AccessToken $accessToken)
    {
        $request->validate([
            'name' => 'string',
            'expiration' => 'date',
            'abilities' => 'string',
        ]);

        $accessToken = AccessToken::where('id', $accessToken->id)->where('user_id', $user->id)->firstOrFail();
        $accessToken->update();
        return new AccessTokenResource($accessToken, 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessToken  $accessToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, AccessToken $accessToken)
    {
        $accessToken = AccessToken::where('id', $accessToken->id)->where('user_id', $user->id)->firstOrFail();
        $accessToken->delete();
        return response()->json(['message' => trans('message.deleted')], 204);
    }
}
