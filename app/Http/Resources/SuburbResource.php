<?php

namespace App\Http\Resources;

use App\Models\Suburb;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SuburbResource
{
    public static function toArray(Model | Collection $data): array
    {
        if ($data instanceof Suburb) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item))->toArray();
    }

    private static function doArray(Suburb $suburb): array
    {
        return [
            'id' => $suburb->getAttribute('id'),
            'name' => $suburb->getAttribute('name'),
            'properties' => 20,
            'createdAt' => $suburb->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $suburb->getAttribute('updated_at')->format('M d, Y')
        ];
    }
}
