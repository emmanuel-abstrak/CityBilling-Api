<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ActionResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    public function login(LoginRequest $request) : JsonResponse
    {
        $request->validated();

        if (auth()->attempt(['email' => $request['email'], 'password' => $request['password']])) {
            /** @var User $user */
            $user = auth()->user();
            return ActionResponse::ok([
                'access_token' => $user->createToken()->plainTextToken,
            ]);
        } else {
            return ActionResponse::badRequest("Wrong login credentials");
        }
    }

    public function refresh(): JsonResponse
    {
        $validated = request()->validate(['token' => 'required']);
        try {
            $request = Request::create('oauth/token', 'POST', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $validated['token'],
                'client_id' => env("PASSPORT_CLIENT_ID"),
                'client_secret' => env("PASSPORT_CLIENT_SECRET"),
                'scope' => '',
            ]);
            $result = app()->handle($request);
            $response = json_decode($result->getContent(), true);
            return ActionResponse::ok([
                'access_token' => $response['access_token'],
                'expires_at' => $response['expires_in'],
                'refresh_token' => $response['refresh_token']
            ]);
        } catch (\Exception $e) {
            return ActionResponse::badRequest("Failed to refresh token");
        }
    }

    public function logout() : JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json([], ResponseAlias::HTTP_NO_CONTENT);
    }
}
