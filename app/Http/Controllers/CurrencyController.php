<?php

namespace App\Http\Controllers;

use App\Http\Requests\Currencies\CurrencyCreateRequest;
use App\Http\Requests\Currencies\CurrencyUpdateRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Responses\ActionResponse;
use App\Models\Currency;
use App\Repositories\Currencies\ICurrencyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CurrencyController extends Controller
{
    public function __construct(
        private readonly ICurrencyRepository $currencyRepository
    ){}

    public function index() : JsonResponse
    {
        Gate::authorize('view', Currency::class);
        $currencies = $this->currencyRepository->getAll(request()->query());
        $currencies->getCollection()->transform(fn ($currency) =>  CurrencyResource::toArray($currency));

        return ActionResponse::ok(paginated_response($currencies));
    }

    public function store(CurrencyCreateRequest $request): JsonResponse
    {
        Gate::authorize('create', Currency::class);
        $validated = $request->validated();
        $currency = $this->currencyRepository->create($validated);

        return ActionResponse::ok(CurrencyResource::toArray($currency));
    }

    public function update(CurrencyUpdateRequest $request, int $id): JsonResponse
    {
        Gate::authorize('update', Currency::class);
        $validated = $request->validated();
        $currency = $this->currencyRepository->update($id, $validated);

        return ActionResponse::ok(CurrencyResource::toArray($currency));
    }

    public function destroy(int $id): JsonResponse
    {
        Gate::authorize('delete', Currency::class);
        $this->currencyRepository->delete($id);
        return ActionResponse::ok("Deleted");
    }
}
