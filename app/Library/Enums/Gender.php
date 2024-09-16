<?php

namespace App\Library\Enums;

enum Gender: string
{
    case female = 'female';
    case male = 'male';
    case other = 'other';

    public static function values(): array
    {
        return [
            'female',
            'male',
            'other'
        ];
    }
}
