<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Currency;

class CurrencyHelper
{
    public static function convert(Currency $currency, float $amount): float
    {
        if (strtolower($currency->getAttribute('code')) !== "usd") {
            return $amount * $currency->getAttribute('exchange_rate');
        }
        return $amount;
    }
}
