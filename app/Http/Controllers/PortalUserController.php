<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\Portal\PortalUserCreateRequest;
use App\Http\Requests\Users\Portal\PortalUserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ActionResponse;
use App\Models\User;
use App\Repositories\Users\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class PortalUserController extends Controller
{
    public function __construct(
        private readonly IUserRepository $userRepository
    ){}

    public function index(): JsonResponse
    {
        Gate::authorize('view', User::class);
        $users = $this->userRepository->getAll(request()->query());
        $users->getCollection()->transform(fn ($user) =>  UserResource::toArray($user));

        return ActionResponse::ok(paginated_response($users));
    }

    public function store(PortalUserCreateRequest $request): JsonResponse
    {
        Gate::authorize('create', User::class);
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);
        $user = $this->userRepository->create($validated);

        return ActionResponse::ok(UserResource::toArray($user));
    }

    public function update(PortalUserUpdateRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();
        $user = $this->userRepository->update($id, $validated);

        return ActionResponse::ok(UserResource::toArray($user));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userRepository->delete($id);
        return ActionResponse::ok("Deleted");
    }
}
