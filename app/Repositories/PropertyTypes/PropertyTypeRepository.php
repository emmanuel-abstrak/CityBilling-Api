<?php

namespace App\Repositories\PropertyTypes;

use App\Models\PropertyType;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PropertyTypeRepository extends BaseRepository implements IPropertyTypeRepository
{

    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = PropertyType::query();
        if (isset($search['search'])) {
            $query->where('name', 'like', '%' . $search['search'] . '%');
        }

        return $query->orderBy('order')->paginate($this->perPage);
    }

    public function getById(int $id): ?PropertyType
    {
        $type = PropertyType::query()->find($id);
        if (!$type) {
            throw new NotFoundResourceException('Type not found');
        }

        return $type;
    }

    public function create(array $attributes): ?PropertyType
    {
        $type = new PropertyType($attributes);
        $type->save();

        return $type->refresh();
    }

    public function update(int $id, array $attributes): ?PropertyType
    {
        $type = $this->getById($id);
        $type->fill($attributes);
        $type->save();

        return $type->refresh();
    }

    public function delete(int $id): ?PropertyType
    {
        $type = $this->getById($id);
        $type->delete();

        return $type;
    }
}
