<?php

namespace App\Http\Resources;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CurrencyResource
{
    public static function toArray(Model | Collection $data): array
    {
        if ($data instanceof Currency) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item))->toArray();
    }

    private static function doArray(Currency $currency): array
    {
        return [
            'id' => $currency->getAttribute('id'),
            'code' => $currency->getAttribute('code'),
            'symbol' => $currency->getAttribute('symbol'),
            'exchangeRate' => $currency->getAttribute('exchange_rate'),
            'createdAt' => $currency->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $currency->getAttribute('updated_at')->format('M d, Y')
        ];
    }
}
