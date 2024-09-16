<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserResource
{
    public static function toArray(Model | Collection $data): array | Collection
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
            'firstName' => $user->getAttribute('first_name'),
            'lastName' => $user->getAttribute('last_name'),
            'initials' => $user->getAttribute('initials'),
            'role' => $user->getAttribute('role'),
            'gender' => $user->getAttribute('gender'),
            'email' => $user->getAttribute('email'),
            'phoneNumber' => $user->getAttribute('phone_number'),
            'idNumber' => $user->getAttribute('id_number'),
            'createdAt' => $user->getAttribute('created_at')->format('M d, Y')
        ];
    }
}
