<?php

namespace App\Repositories\Suburbs;

use App\Models\Suburb;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class SuburbRepository extends BaseRepository implements ISuburbRepository
{

    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = Suburb::query();
        if (isset($search['search'])) {
            $query->where('name', 'like', '%' . $search['search'] . '%');
        }

        return $query->orderBy('name')->paginate($this->perPage);
    }

    public function getById(int $id): ?Suburb
    {
        $suburb = Suburb::query()->find($id);
        if (!$suburb) {
            throw new NotFoundResourceException('Suburb not found');
        }

        return $suburb;
    }

    public function create(array $attributes): ?Suburb
    {
        $suburb = new Suburb($attributes);
        $suburb->save();

        return $suburb->refresh();
    }

    public function update(int $id, array $attributes): ?Suburb
    {
        $suburb = $this->getById($id);
        $suburb->fill($attributes);
        $suburb->save();

        return $suburb->refresh();
    }

    public function delete(int $id): ?Suburb
    {
        $suburb = $this->getById($id);
        $suburb->delete();
        return $suburb;
    }
}
