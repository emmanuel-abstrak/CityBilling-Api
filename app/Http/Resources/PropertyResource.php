<?php

namespace App\Http\Resources;

use App\Models\Property;
use App\Models\TariffGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PropertyResource
{
    public static function toArray(Model | Collection $data): array
    {
        if ($data instanceof Property) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item))->toArray();
    }

    private static function doArray(Property $property): array
    {
        return [
            'id' => $property->getAttribute('id'),
            'type' => $property->getAttribute('type'),
            'size' => $property->getAttribute('size'),
            'meter' => $property->getAttribute('meter'),
            'meter_provider' => $property->getAttribute('meter_provider'),
            'address' => $property->getAttribute('address'),
            'ratesCharge' => $property->getAttribute('rates_charge'),
            'refuseCharge' => $property->getAttribute('refuse_charge'),
            'sewerCharge' => $property->getAttribute('sewer_charge'),
            'balances' => $property->getAttribute('balances'),
            'owner' => $property->getAttribute('owner') ? UserResource::toArray($property->getAttribute('owner')) : null,
            'suburb' => $property->getAttribute('suburb') ? [
                'id' => $property->getAttribute('suburb')->getAttribute('id'),
                'name' => $property->getAttribute('suburb')->getAttribute('name'),
            ] : null,
            'createdAt' => $property->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $property->getAttribute('updated_at')->format('M d, Y'),
        ];
    }
}
