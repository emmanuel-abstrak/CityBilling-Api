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
            'exchange_rate' => $currency->getAttribute('exchange_rate'),
            'created_at' => $currency->getAttribute('created_at'),
            'updated_at' => $currency->getAttribute('updated_at')
        ];
    }
}
