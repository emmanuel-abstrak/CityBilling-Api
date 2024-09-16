<?php

namespace App\Repositories\Activities;

use App\Models\Activity;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ActivityRepository extends BaseRepository implements IActivityRepository
{

    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = Activity::query();
        if (isset($search['search'])) {
            $query->join('users', 'users.id', 'activities.user_id');
            $query->where(function ($q) use ($search) {
                $q->orWhere('users.email', 'like', '%' . $search['search'] . '%');
                $q->orWhere('users.first_name', 'like', '%' . $search['search'] . '%');
                $q->orWhere('users.last_name', 'like', '%' . $search['search'] . '%');
                $q->orWhere('users.phone_number', 'like', '%' . $search['search'] . '%');
                $q->orWhere('activities.action', 'like', '%' . $search['search'] . '%');
            });
        }

        return $query->orderBy('activities.id', 'desc')->paginate($search['limit'] ?? $this->perPage);
    }

    public function getById(int $id): ?Model
    {
        $activity = Activity::query()->find($id);
        if (!$activity) {
            throw new NotFoundResourceException('Activity not found');
        }

        return $activity;
    }

    public function create(array $attributes): ?Model
    {
        $activity = new Activity($attributes);
        $activity->save();

        return $activity->refresh();
    }

    public function update(int $id, array $attributes): ?Model
    {
        $activity = $this->getById($id);
        $activity->fill($attributes);
        $activity->save();

        return $activity->refresh();
    }

    public function delete(int $id): ?Model
    {
        $activity = $this->getById($id);
        $activity->delete();
        return $activity;
    }
}

