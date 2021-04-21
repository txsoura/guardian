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
    public function index()
    {
        return AccessTokenResource::collection(AccessToken::where('user_id', auth()->user()->id)->orderBy('last_used_at', 'desc')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'expiration' => 'nullable|date',
            'abilities' => 'required|string',
        ]);

        $token = AccessToken::create([
            "user_id" => auth()->user()->id,
            'token' => Hash::make(Str::random(13)),
            'name' => $request['name'],
            'expiration' => $request['expiration'],
            'abilities' => $request['abilities']
        ]);

        return new AccessTokenResource($token, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessToken  $token
     * @return \Illuminate\Http\Response
     */
    public function show(AccessToken $token)
    {
        return new AccessTokenResource(AccessToken::where('id', $token->id)->where('user_id', auth()->user()->id)->firstOrFail(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccessToken  $token
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccessToken $token)
    {
        $request->validate([
            'name' => 'required|string',
            'expiration' => 'nullable|date',
            'abilities' => 'required|string',
        ]);

        $token = AccessToken::where('id', $token->id)->where('user_id',  auth()->user()->id)->firstOrFail();
        $token->name=$request['name'];
        $token->expiration=$request['expiration'];
        $token->abilities=$request['abilities'];
        $token->update();

        return new AccessTokenResource($token, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessToken  $token
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccessToken $token)
    {
        $token = AccessToken::where('id', $token->id)->where('user_id',  auth()->user()->id)->firstOrFail();
        $token->delete();
        return response()->json(['message' => trans('message.deleted')], 200);
    }
}
