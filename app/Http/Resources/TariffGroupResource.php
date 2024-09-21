<?php

namespace App\Http\Resources;

use App\Models\TariffGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TariffGroupResource
{
    public static function toArray(Model | Collection $data): array
    {
        if ($data instanceof TariffGroup) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item))->toArray();
    }

    private static function doArray(TariffGroup $tariff): array
    {
        return [
            'id' => $tariff->getAttribute('id'),
            'minSize' => $tariff->getAttribute('min_size'),
            'maxSize' => $tariff->getAttribute('max_size'),
            'createdAt' => $tariff->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $tariff->getAttribute('updated_at')->format('M d, Y'),
            'tariffs' => $tariff->getAttribute('tariffs')->map(function($charge) {
                return [
                    'id' => $charge->getAttribute('id'),
                    'propertyType' => $charge->getAttribute('propertyType')->getAttribute('name'),
                    'service' => $charge->getAttribute('service')->getAttribute('name'),
                    'price' => money_currency($charge->getAttribute('price')),
                ];
            }),
            'suburb' => [
                'id' => $tariff->getAttribute('suburb')->getAttribute('id'),
                'name' => $tariff->getAttribute('suburb')->getAttribute('name'),
            ]
        ];
    }
}
