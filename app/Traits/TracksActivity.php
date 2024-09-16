<?php

namespace App\Traits;

use App\Library\Enums\UserRole;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use ReflectionClass;

trait TracksActivity
{
    protected static function bootTracksActivity(): void
    {
        static::created(function(Model $model) {
            if(self::canLog()) {
                (new Activity([
                    'user_id' => auth()->id(),
                    'action' => 'Created: ' . self::getClassName($model),
                    'after' => json_encode(self::clean($model->getAttributes()))
                ]))->save();
            }
        });

        static::updating(function(Model $model) {
            if (self::canLog()) {
                (new Activity([
                    'user_id' => auth()->id(),
                    'action' => 'Updated: ' . self::getClassName($model),
                    'before' => json_encode(self::clean($model->getOriginal())),
                    'after' => json_encode(self::clean($model->getAttributes()))
                ]))->save();
            }
        });

        static::deleting(function(Model $model) {
            if (self::canLog()) {
                (new Activity([
                    'user_id' => auth()->id(),
                    'action' => 'Deleted: ' . self::getClassName($model),
                    'before' => json_encode(self::clean($model->getAttributes()))
                ]))->save();
            }
        });
    }

    private static function getClassName(object $instance): string
    {
        return (new ReflectionClass($instance))->getShortName();
    }

    private static function clean(array $data): array
    {
        return Arr::except($data, ['id', 'updated_at', 'created_at', 'password']);
    }

    private static function canLog(): bool
    {
        /** @var User $user */
        $user = auth()->user();
        return $user && $user->getAttribute('role') != UserRole::user->value;
    }
}
