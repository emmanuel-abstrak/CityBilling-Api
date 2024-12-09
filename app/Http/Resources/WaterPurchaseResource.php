<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\WaterPurchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WaterPurchaseResource
{
    public static function toArray(Model | Collection $data): array | Collection
    {
        if ($data instanceof WaterPurchase) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item));
    }

    private static function doArray(WaterPurchase $purchase): array
    {
        return [
            'id' => $purchase->getAttribute('id'),
            'meter' => $purchase->property->getAttribute('meter'),
            'currency' => $purchase->currency->getAttribute('code'),
            'amount' => $purchase->getAttribute('token_amount'),
            'unitPrice' => $purchase->getAttribute('price'),
            'vat' => $purchase->getAttribute('vat'),
            'tariffs' => $purchase->getAttribute('tariffs'),
            'volume' => $purchase->getAttribute('volume'),
            'token' => $purchase->getAttribute('token'),
            'createdAt' => $purchase->getAttribute('created_at')
        ];
    }
}
