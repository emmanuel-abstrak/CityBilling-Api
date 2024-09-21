<?php

namespace App\Repositories\Properties;

use App\Models\Property;
use App\Models\PropertyStatement;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PropertyRepository extends BaseRepository implements IPropertyRepository
{
    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = Property::query()->select('properties.*');
        $query->join('users', 'users.id', 'properties.owner_id');
        if (isset($search['search'])) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('users.first_name', 'like', '%' . $search['search'] . '%');
                $q->orWhere('users.last_name', 'like', '%' . $search['search'] . '%');
                $q->orWhere('users.email', 'like', '%' . $search['search'] . '%');
                $q->orWhere('users.phone_number', 'like', '%' . $search['search'] . '%');
                $q->orWhere('users.id', 'like', '%' . $search['search'] . '%');
                $q->orWhere('properties.meter', 'like', '%' . $search['search'] . '%');
            });
        }

        if (isset($search['suburb'])) {
            $query->where('properties.suburb_id', $search['suburb']);
        }

        if (isset($search['user'])) {
            $query->where('owner_id', $search['user']);
        }

        return $query->paginate($this->perPage);
    }

    public function getById(int $id): ?Property
    {
        $property = Property::query()->find($id);
        if (!$property) {
            throw new NotFoundResourceException('Property not found');
        }

        return $property;
    }

    /**
     * @throws ValidationException
     */
    public function create(array $attributes): ?Property
    {
        $check = Property::query()->where([
            'suburb_id' => $attributes['suburb_id'],
            'address' => $attributes['address']
        ])->first();

        if ($check) {
            throw ValidationException::withMessages(['code' => 'Property already exists']);
        }

        $property = new Property($attributes);
        $property->save();

        return $property->refresh();
    }

    public function update(int $id, array $attributes): ?Property
    {
        $property = $this->getById($id);

        $property->update($attributes);
        return $property->refresh();
    }

    public function delete(int $id): ?Property
    {
        $property = $this->getById($id);

        $property->delete();
        return $property;
    }

    public function getCurrentMonthBalanceTotal(): float
    {
        $balance = 0;

        PropertyStatement::query()->whereBetween('created_at',
            [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]
        )->each(function(PropertyStatement $statement) use(&$balance) {
            $balance += $statement->getAttribute('total') - $statement->getAttribute('paid');
        });

        return $balance;
    }

    public function getByMeter(string $meter): ?Property
    {
        $property = Property::query()->where('meter', $meter)->first();
        if (!$property) {
            throw new NotFoundResourceException('Property not found');
        }

        return $property;
    }
}
