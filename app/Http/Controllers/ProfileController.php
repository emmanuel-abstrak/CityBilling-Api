<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Responses\ActionResponse;
use App\Repositories\Users\IUserRepository;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function __construct(private readonly IUserRepository $userRepository){}

    public function user() : JsonResponse
    {
        $user = $this->userRepository->getById(auth()->id());
        return ActionResponse::ok(UserResource::toArray($user));
    }
}
