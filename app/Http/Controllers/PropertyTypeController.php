<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyTypes\PropertyTypeCreateRequest;
use App\Http\Requests\PropertyTypes\PropertyTypeUpdateRequest;
use App\Http\Resources\PropertyTypeResource;
use App\Http\Responses\ActionResponse;
use App\Models\PropertyType;
use App\Repositories\PropertyTypes\IPropertyTypeRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PropertyTypeController extends Controller
{
    public function __construct(
        private readonly IPropertyTypeRepository $propertyTypeRepository,
    ){}

    public function index(): JsonResponse
    {
        Gate::authorize('view', PropertyType::class);
        $types = $this->propertyTypeRepository->getAll(request()->query());
        $types->getCollection()->transform(fn ($type) =>  PropertyTypeResource::toArray($type));

        return ActionResponse::ok(paginated_response($types));
    }

    public function store(PropertyTypeCreateRequest $request): JsonResponse
    {
        Gate::authorize('create', PropertyType::class);
        $validated = $request->validated();
        $type = $this->propertyTypeRepository->create($validated);

        return ActionResponse::ok(PropertyTypeResource::toArray($type));
    }

    public function update(PropertyTypeUpdateRequest $request, int $id): JsonResponse
    {
        Gate::authorize('update', PropertyType::class);
        $validated = $request->validated();
        $type = $this->propertyTypeRepository->update($id, $validated);

        return ActionResponse::ok(PropertyTypeResource::toArray($type));
    }

    public function destroy(int $id): JsonResponse
    {
        Gate::authorize('delete', PropertyType::class);
        $this->propertyTypeRepository->delete($id);
        return ActionResponse::ok("Deleted");
    }
}
