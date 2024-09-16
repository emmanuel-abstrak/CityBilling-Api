<?php

namespace App\Library\Enums;

enum UserRole: string
{
    case sudo = 'sudo';
    case admin = 'admin';
    case clerk = 'clerk';
    case user = 'user';

    public static function values(): array
    {
        return [
            'sudo',
            'admin',
            'clerk',
            'user'
        ];
    }

    public static function elevated(): array
    {
        return [
            'sudo',
            'admin'
        ];
    }

    public static function portal(): array
    {
        return [
            'sudo',
            'admin',
            'clerk',
        ];
    }
}
