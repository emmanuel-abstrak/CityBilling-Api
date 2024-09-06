<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserResource
{
    public static function toArray(Model | Collection $data): array
    {
        if ($data instanceof User) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item));
    }

    private static function doArray(User $user): array
    {
        return [
            'id' => $user->getAttribute('id'),
            'first_name' => $user->getAttribute('first_name'),
            'last_name' => $user->getAttribute('last_name'),
            'user_type' => $user->getAttribute('user_type'),
            'email' => $user->getAttribute('email'),
            'phone_number' => $user->getAttribute('phone_number'),
            'created_at' => $user->getAttribute('created_at'),
            'updated_at' => $user->getAttribute('updated_at')
        ];
    }
}
