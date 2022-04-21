<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccessTokenResource;
use App\Models\AccessToken;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccessTokenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return AccessTokenResource::collection(
            AccessToken::where('user_id', auth()->user()->id)
                ->orderBy('last_used_at', 'desc')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return AccessTokenResource
     */
    public function store(Request $request): AccessTokenResource
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
     * @param AccessToken $token
     * @return AccessTokenResource|null
     */
    public function show(AccessToken $token): AccessTokenResource
    {
        return new AccessTokenResource(
            AccessToken::where('id', $token->id)
                ->where('user_id', auth()->user()->id)
                ->firstOrFail()
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param AccessToken $token
     * @return AccessTokenResource
     */
    public function update(Request $request, AccessToken $token): AccessTokenResource
    {
        $request->validate([
            'name' => 'required|string',
            'expiration' => 'nullable|date',
            'abilities' => 'required|string',
        ]);

        $token = AccessToken::where('id', $token->id)->where('user_id', auth()->user()->id)->firstOrFail();
        $token->name = $request['name'];
        $token->expiration = $request['expiration'];
        $token->abilities = $request['abilities'];
        $token->update();

        return new AccessTokenResource($token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AccessToken $token
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(AccessToken $token): JsonResponse
    {
        $token = AccessToken::where('id', $token->id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $token->delete();

        return response()->json(['message' => trans('message.deleted')]);
    }
}
