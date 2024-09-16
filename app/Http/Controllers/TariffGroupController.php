<?php

namespace App\Http\Controllers;

use App\Http\Requests\TariffGroups\TariffGroupCreateRequest;
use App\Http\Requests\TariffGroups\TariffGroupUpdateRequest;
use App\Http\Resources\TariffGroupResource;
use App\Http\Responses\ActionResponse;
use App\Models\TariffGroup;
use App\Policies\TariffGroupPolicy;
use App\Repositories\TariffGroups\ITariffGroupRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TariffGroupController extends Controller
{
    public function __construct(
        private readonly ITariffGroupRepository $tariffGroupRepository
    ){}

    public function index(): JsonResponse
    {
        Gate::authorize('view', TariffGroup::class);
        $tariffs = $this->tariffGroupRepository->getAll(request()->query());
        $tariffs->getCollection()->transform(fn ($tariff) =>  TariffGroupResource::toArray($tariff));

        return ActionResponse::ok(paginated_response($tariffs));
    }

    public function store(TariffGroupCreateRequest $request): JsonResponse
    {
        Gate::authorize('create', TariffGroup::class);
        $validated = $request->validated();
        $tariff = $this->tariffGroupRepository->create($validated);

        return ActionResponse::ok(TariffGroupResource::toArray($tariff));
    }

    public function update(TariffGroupUpdateRequest $request, int $id): JsonResponse
    {
        Gate::authorize('update', TariffGroup::class);
        $validated = $request->validated();
        $tariff = $this->tariffGroupRepository->update($id, $validated);

        return ActionResponse::ok(TariffGroupResource::toArray($tariff));
    }

    public function destroy(int $id): JsonResponse
    {
        Gate::authorize('delete', TariffGroup::class);
        $this->tariffGroupRepository->delete($id);
        return ActionResponse::ok("Deleted");
    }
}
