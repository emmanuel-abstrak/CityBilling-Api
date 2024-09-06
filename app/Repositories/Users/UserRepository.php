<?php

namespace App\Repositories\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements IUserRepository
{
    public function getAll(array $search = []): Collection
    {
        return User::query()->get();
    }

    public function getById($id): ?User
    {
        return User::query()->find($id);
    }

    public function create(array $attributes): ?User
    {
        $user = new User($attributes);
        $user->save();
        return $user->refresh();
    }

    public function update($id, array $attributes): ?User
    {
        $user = $this->getById($id);
        if (!is_object($user)) return null;

        $user->update($attributes);
        return $user->refresh();
    }

    public function delete($id): ?User
    {
        $user = $this->getById($id);
        if (!is_object($user)) return null;

        $user->delete();
        return $user;
    }

    public function getByEmail(string $email): ?Model
    {
        return User::query()->where('email', $email)->first();
    }
}
