<?php

namespace App\Http\Resources;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PropertyTypeResource
{
    public static function toArray(Model | Collection $data): array
    {
        if ($data instanceof PropertyType) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item))->toArray();
    }

    private static function doArray(PropertyType $type): array
    {
        return [
            'id' => $type->getAttribute('id'),
            'name' => $type->getAttribute('name'),
            'cutoff' => $type->getAttribute('cutoff'),
            'cutoffPrice' => $type->getAttribute('cutoff_price'),
            'price' => $type->getAttribute('price'),
            'properties' => $type->getAttribute('properties')->count(),
            'createdAt' => $type->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $type->getAttribute('updated_at')->format('M d, Y')
        ];
    }
}
