<?php

namespace App\Helpers;

use App\Models\Property;
use App\Models\WaterPurchase;
use Carbon\Carbon;

class WaterPurchaseHelper
{
    public static function getCurrentMonthVolume(Property $property): float
    {
        return $property
            ->getAttribute('waterPurchases')
            ->where('status', WaterPurchase::STATUS_COMPLETED)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('volume');
    }
}
