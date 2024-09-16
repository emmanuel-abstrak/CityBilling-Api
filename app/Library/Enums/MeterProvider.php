<?php

namespace App\Library\Enums;

enum MeterProvider : string
{
    case calin = 'calin';
    case wuxi = 'wuxi';
    case stron = 'stron';

    public static function values(): array
    {
        return ['calin', 'wuxi', 'stron'];
    }
}
