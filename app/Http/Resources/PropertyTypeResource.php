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
            'createdAt' => $type->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $type->getAttribute('updated_at')->format('M d, Y')
        ];
    }
}
