<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ActionResponse;
use App\Models\User;
use App\Repositories\Users\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct(private readonly IUserRepository $userRepository){}

    public function user() : JsonResponse
    {
        $user = $this->userRepository->getById(auth()->id());
        return ActionResponse::ok(UserResource::toArray($user));
    }

    public function changePassword(ChangePasswordRequest $request) : JsonResponse
    {
        $validated = $request->validated();

        /** @var User $user */
        $user = auth()->user();
        if (Hash::check($validated['current_password'], $user->getAttribute('password'))) {
            $this->userRepository->changePassword(auth()->id(), $validated['new_password']);

            return ActionResponse::ok("Updated password");
        }

        return ActionResponse::badRequest("Wrong current password");
    }
}
