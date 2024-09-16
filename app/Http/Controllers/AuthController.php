<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ActionResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
                'user' => [
                    'firstName' => $user->getAttribute('first_name'),
                    'lastName' => $user->getAttribute('last_name'),
                    'email' => $user->getAttribute('email'),
                    'role' => $user->getAttribute('role')
                ]
            ]);
        } else {
            return ActionResponse::badRequest("Wrong login credentials");
        }
    }

    public function logout() : JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json([], ResponseAlias::HTTP_NO_CONTENT);
    }
}
