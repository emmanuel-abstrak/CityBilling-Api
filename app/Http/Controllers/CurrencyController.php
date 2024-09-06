<?php

namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Http\Responses\ActionResponse;
use App\Repositories\Currencies\ICurrencyRepository;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    public function __construct(private readonly ICurrencyRepository $currencyRepository){}

    public function index() : JsonResponse
    {
        $currencies = $this->currencyRepository->getAll();
        return ActionResponse::ok(CurrencyResource::toArray($currencies));
    }

    public function show(int $id) : JsonResponse
    {
        $currency = $this->currencyRepository->getById($id);
        if (is_null($currency)) {
            return ActionResponse::notFound('Currency not found.');
        }
        return ActionResponse::ok(CurrencyResource::toArray($currency));
    }
}
