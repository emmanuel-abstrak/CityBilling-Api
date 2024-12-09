<?php

namespace App\Http\Controllers;

use App\Http\Resources\WaterPurchaseResource;
use App\Models\WaterPurchase;
use Illuminate\Http\JsonResponse;

class WaterPurchaseController extends Controller
{
    public function show(int $purchaseId): JsonResponse
    {
        $purchase = WaterPurchase::with(['property', 'currency'])->findOrFail($purchaseId);
        return response()->json(WaterPurchaseResource::toArray($purchase));
    }
}
