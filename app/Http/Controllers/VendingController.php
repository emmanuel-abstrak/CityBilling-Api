<?php

namespace App\Http\Controllers;

use App\Helpers\VendingHelper;
use App\Http\Requests\Vending\MeterLookupRequest;
use App\Http\Responses\ActionResponse;
use App\Models\Currency;
use App\Repositories\Currencies\ICurrencyRepository;
use App\Repositories\WaterPurchases\IWaterPurchaseRepository;
use App\ServiceProviders\Shared\IServiceProvider;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Helpers\PaynowHelper;
use App\Models\WaterPurchase;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class VendingController extends Controller
{
    public function __construct(
        private readonly IServiceProvider $serviceProvider,
        private readonly ICurrencyRepository $currencyRepository,
        private readonly IWaterPurchaseRepository $waterPurchaseRepository,
    ) {}

    public function meterLookup(MeterLookupRequest $request): JsonResponse
    {
        $request->validated();
        $summary = $this->doLookup();
        return ActionResponse::ok($summary);
    }

    public function buy(MeterLookupRequest $request): JsonResponse
    {
        $request->validated();
        $summary = $this->doLookup();

        $tariffs = $summary['balances'];

        $purchase = $this->waterPurchaseRepository->create([
            'property_id' => $summary['property_id'],
            'currency_id' => $summary['currency_id'],
            'requested_amount' => request('amount'),
            'price' => $summary['meter']['price'],
            'token_amount' => $summary['tokenAmount'],
            'volume' => $summary['volume'],
            'vat' => $summary['vat'],
            'tariffs' => json_encode($tariffs),
        ]);

        $paynow = (new PaynowHelper($purchase))->getInstance();
        $ref = sprintf("%s_%s", $purchase->id, request('meter'));
        $payment = $paynow->createPayment($ref, null);
        $payment->add('Water', $summary['amount']);

        $response = $paynow->send($payment);
        $purchase->setAttribute('redirect_url', $response->redirectUrl());
        $purchase->setAttribute('poll_url', $response->pollUrl());
        $purchase->save();
        $summary['purchase_id'] = $purchase->id;
        $summary['redirect_url'] = $response->redirectUrl();
        return ActionResponse::ok($summary);
    }

    public function callback(int $purchaseId): View
    {
        $purchase = $this->waterPurchaseRepository->getById($purchaseId);
        if (!$purchase) {
            abort(404);
        }

        if ($purchase->status == WaterPurchase::STATUS_PENDING) {
            $paynow = new PaynowHelper($purchase);
            $paynow->settlePayment();
            $purchase->refresh();
        }

        return view('vending.callback', compact('purchase'));
    }

    private function doLookup(): array
    {
        $meterDetail = $this->serviceProvider->lookUp(request('meter'));
        if (!$meterDetail) {
            throw new BadRequestException('Failed to get meter');
        }

        /** @var Currency $currency */
        $currency = $this->currencyRepository->getByCode(request('currency'));

        return VendingHelper::getLookupSummary($meterDetail, $currency, request('amount'));
    }
}
