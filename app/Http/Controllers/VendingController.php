<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vending\MeterLookupRequest;
use App\Http\Responses\ActionResponse;
use App\Models\Currency;
use App\Repositories\Currencies\ICurrencyRepository;
use App\ServiceProviders\Shared\IServiceProvider;
use Illuminate\Http\JsonResponse;

class VendingController extends Controller
{
    public function __construct(
        private readonly IServiceProvider $serviceProvider,
        private readonly ICurrencyRepository $currencyRepository,
    ){}

    public function meterLookup(MeterLookupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $meterDetail = $this->serviceProvider->lookUp($validated['meter']);
        if (!$meterDetail) {
            return ActionResponse::notFound("Meter not found");
        }

        /** @var Currency $currency */
        $currency = $this->currencyRepository->getByCode($validated['currency']);
        $property = $meterDetail->getProperty();

        $summary = $property->getLookupSummary($meterDetail, $validated['amount'], $currency);

        $summary['meter'] = $meterDetail->toArray();

        return ActionResponse::ok($summary);
    }

    public function buy(MeterLookupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $meterDetail = $this->serviceProvider->lookUp($validated['meter']);
        if (!$meterDetail) {
            return ActionResponse::notFound("Meter not found");
        }

        /** @var Currency $currency */
        $currency = $this->currencyRepository->getByCode($validated['currency']);
        $property = $meterDetail->getProperty();

        $summary = $property->getLookupSummary($meterDetail, $validated['amount'], $currency);

        $summary['meter'] = $meterDetail->toArray();

        return ActionResponse::ok($summary);
    }
}
