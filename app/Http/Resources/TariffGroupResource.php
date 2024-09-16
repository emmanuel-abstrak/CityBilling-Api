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
            'residentialRatesCharge' => $tariff->getAttribute('residential_rates_charge'),
            'residentialRefuseCharge' => $tariff->getAttribute('residential_refuse_charge'),
            'residentialSewerageCharge' => $tariff->getAttribute('residential_sewerage_charge'),
            'commercialRatesCharge' => $tariff->getAttribute('commercial_rates_charge'),
            'commercialRefuseCharge' => $tariff->getAttribute('commercial_refuse_charge'),
            'commercialSewerageCharge' => $tariff->getAttribute('commercial_sewerage_charge'),
            'createdAt' => $tariff->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $tariff->getAttribute('updated_at')->format('M d, Y'),
            'suburb' => [
                'id' => $tariff->getAttribute('suburb')->getAttribute('id'),
                'name' => $tariff->getAttribute('suburb')->getAttribute('name'),
            ]
        ];
    }
}
