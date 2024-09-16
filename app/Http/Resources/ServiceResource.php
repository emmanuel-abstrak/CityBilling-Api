<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ServiceResource
{
    public static function toArray(Model | Collection $data): array
    {
        if ($data instanceof Service) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item))->toArray();
    }

    private static function doArray(Service $service): array
    {
        return [
            'id' => $service->getAttribute('id'),
            'name' => $service->getAttribute('name'),
            'order' => $service->getAttribute('order'),
            'createdAt' => $service->getAttribute('created_at')->format('M d, Y'),
            'updatedAt' => $service->getAttribute('updated_at')->format('M d, Y')
        ];
    }
}
