<?php

namespace App\Http\Controllers;


use App\Http\Requests\Services\ServiceCreateRequest;
use App\Http\Requests\Services\ServiceReorderRequest;
use App\Http\Requests\Services\ServiceUpdateRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Responses\ActionResponse;
use App\Models\Service;
use App\Repositories\Services\IServiceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function __construct(
        private readonly IServiceRepository $serviceRepository,
    ){}

    public function index(): JsonResponse
    {
        Gate::authorize('view', Service::class);
        $services = $this->serviceRepository->getAll(request()->query());
        $services->getCollection()->transform(fn ($service) =>  ServiceResource::toArray($service));

        return ActionResponse::ok(paginated_response($services));
    }

    public function store(ServiceCreateRequest $request): JsonResponse
    {
        Gate::authorize('create', Service::class);
        $validated = $request->validated();
        $service = $this->serviceRepository->create($validated);

        return ActionResponse::ok(ServiceResource::toArray($service));
    }

    public function update(ServiceUpdateRequest $request, int $id): JsonResponse
    {
        Gate::authorize('update', Service::class);
        $validated = $request->validated();
        $service = $this->serviceRepository->update($id, $validated);

        return ActionResponse::ok(ServiceResource::toArray($service));
    }

    public function reorder(ServiceReorderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if ($this->serviceRepository->reorder($validated['order'])) {
            return ActionResponse::ok("Success");
        }

        return ActionResponse::badRequest("Something went wrong");
    }

    public function destroy(int $id): JsonResponse
    {
        Gate::authorize('delete', Service::class);
        $this->serviceRepository->delete($id);
        return ActionResponse::ok("Deleted");
    }
}
