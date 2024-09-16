<?php

namespace App\ServiceProviders;

use App\Models\Property;
use App\ServiceProviders\Shared\MeterDetail;

class Wuxi
{
    public static function lookup(?Property $property = null, ?string $meter = null): ?MeterDetail
    {
        return null;
    }
}
