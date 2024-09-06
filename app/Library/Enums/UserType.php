<?php

namespace App\Library\Enums;

enum UserType
{
    case sudo;
    case admin;
    case user;

    public static function values(): array
    {
        return [
            'sudo',
            'admin',
            'user'
        ];
    }
}
