<?php

namespace App\Http\Controllers;

use App\Http\Requests\Suburbs\SuburbCreateRequest;
use App\Http\Requests\Suburbs\SuburbUpdateRequest;
use App\Http\Resources\SuburbResource;
use App\Http\Responses\ActionResponse;
use App\Models\Suburb;
use App\Repositories\Suburbs\ISuburbRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class SuburbController extends Controller
{
    public function __construct(
        private readonly ISuburbRepository $suburbRepository,
    ){}

    public function index(): JsonResponse
    {
        Gate::authorize('view', Suburb::class);
        $suburbs = $this->suburbRepository->getAll(request()->query());
        $suburbs->getCollection()->transform(fn ($suburb) =>  SuburbResource::toArray($suburb));

        return ActionResponse::ok(paginated_response($suburbs));
    }

    public function store(SuburbCreateRequest $request): JsonResponse
    {
        Gate::authorize('create', Suburb::class);
        $validated = $request->validated();
        $suburb = $this->suburbRepository->create($validated);

        return ActionResponse::ok(SuburbResource::toArray($suburb));
    }

    public function update(SuburbUpdateRequest $request, int $id): JsonResponse
    {
        Gate::authorize('update', Suburb::class);
        $validated = $request->validated();
        $suburb = $this->suburbRepository->update($id, $validated);

        return ActionResponse::ok(SuburbResource::toArray($suburb));
    }

    public function destroy(int $id): JsonResponse
    {
        Gate::authorize('delete', Suburb::class);
        $this->suburbRepository->delete($id);
        return ActionResponse::ok("Deleted");
    }
}
