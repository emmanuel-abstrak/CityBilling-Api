<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = User::query();

        if (isset($search['search'])) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('email', 'like', '%' . $search['search'] . '%');
                $q->orWhere('first_name', 'like', '%' . $search['search'] . '%');
                $q->orWhere('last_name', 'like', '%' . $search['search'] . '%');
                $q->orWhere('phone_number', 'like', '%' . $search['search'] . '%');
            });
        }

        if (isset($search['role'])) {
            $query = User::query()->where('role', $search['role']);
        }

        return $query->orderBy('id', 'desc')->paginate($this->perPage);
    }

    public function getById($id): ?User
    {
        $user = User::query()->find($id);
        if (!is_object($user)) {
            throw new NotFoundResourceException('User not found');
        }

        return $user;
    }

    public function create(array $attributes): ?User
    {
        $attributes['first_name'] = ucwords($attributes['first_name']);
        $attributes['last_name'] = ucwords($attributes['last_name']);
        $attributes['email'] = strtolower($attributes['email']);
        $attributes['role'] = strtolower($attributes['role']);

        $user = new User($attributes);
        $user->save();
        // Send Email
        return $user->refresh();
    }

    /**
     * @throws ValidationException
     */
    public function update($id, array $attributes): ?User
    {
        if ($this->existsExcept($attributes['email'], $id)) {
            throw ValidationException::withMessages(['email' => 'Email already taken']);
        }
        $user = $this->getById($id);
        $user->update($attributes);
        return $user->refresh();
    }

    public function delete($id): ?User
    {
        $user = $this->getById($id);
        $user->delete();
        return $user;
    }

    public function getByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function existsExcept(string $email, int $userId): bool
    {
        return User::query()->where('email', $email)->whereNot('id', $userId)->count() > 0;
    }
}
