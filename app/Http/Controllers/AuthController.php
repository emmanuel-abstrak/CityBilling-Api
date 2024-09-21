<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Responses\ActionResponse;
use App\Mail\ForgotPasswordMail;
use App\Mail\SendOwnerPropertyCreatedMail;
use App\Models\User;
use App\Repositories\Users\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    public function __construct(private readonly IUserRepository $userRepository) {}

    public function login(LoginRequest $request) : JsonResponse
    {
        $request->validated();

        if (auth()->attempt(['email' => $request['email'], 'password' => $request['password']])) {
            /** @var User $user */
            $user = auth()->user();
            return ActionResponse::ok([
                'accessToken' => $user->createToken()->plainTextToken,
                'id' => $user->getAttribute('id'),
                'firstName' => $user->getAttribute('first_name'),
                'lastName' => $user->getAttribute('last_name'),
                'email' => $user->getAttribute('email'),
                'phoneNumber' => $user->getAttribute('phone_number'),
                'role' => $user->getAttribute('role')
            ]);
        } else {
            return ActionResponse::badRequest("Wrong login credentials");
        }
    }

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = $this->userRepository->getByEmail($validated['email']);
        $passwordCode = generate_unique_pin();

        $user->setAttribute('password_code', $passwordCode);
        $user->save();
        $user->refresh();

        if(Mail::to($user->getAttribute('email'))->send(new ForgotPasswordMail($user))) {
            return ActionResponse::ok("success");
        }

        return ActionResponse::error("Request failed, please try again");
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $this->userRepository->getByEmail($validated['email']);

        if ($user->getAttribute('password_code') == $validated['code']) {
            $this->userRepository->changePassword($user->getAttribute('id'), $validated['password']);

            return ActionResponse::ok("password changed successfully");
        } else {
            return ActionResponse::badRequest("Wrong password reset code");
        }
    }

    public function logout() : JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json([], ResponseAlias::HTTP_NO_CONTENT);
    }
}
