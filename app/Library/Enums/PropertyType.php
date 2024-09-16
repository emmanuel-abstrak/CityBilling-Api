<?php

namespace App\Library\Enums;

enum PropertyType: string
{
    case commercial = 'commercial';
    case residential = 'residential';

    public static function values(): array
    {
        return [
            'commercial',
            'residential'
        ];
    }
}
