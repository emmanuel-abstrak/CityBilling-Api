<?php

namespace App\Http\Resources;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ActivityResource
{
    public static function toArray(Model | Collection $data): array | Collection
    {
        if ($data instanceof Activity) {
            return self::doArray($data);
        }
        return $data->map(fn ($item) => self::doArray($item));
    }

    private static function doArray(Activity $activity): array
    {
        return [
            'id' => $activity->getAttribute('id'),
            'action' => $activity->getAttribute('action'),
            'before' => $activity->getAttribute('before'),
            'after' => $activity->getAttribute('after'),
            'createdAt' => $activity->getAttribute('created_at')->format('M d, Y H:i'),
            'actor' => [
                'id' => $activity->getAttribute('actor')->getAttribute('id'),
                'role' => $activity->getAttribute('actor')->getAttribute('role'),
                'initials' => $activity->getAttribute('actor')->getAttribute('initials'),
                'firstName' => $activity->getAttribute('actor')->getAttribute('first_name'),
                'lastName' => $activity->getAttribute('actor')->getAttribute('last_name'),
                'email' => $activity->getAttribute('actor')->getAttribute('email'),
                'phoneNumber' => $activity->getAttribute('actor')->getAttribute('phone_number'),
            ],
        ];
    }
}
