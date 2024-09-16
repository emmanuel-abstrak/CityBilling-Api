<?php

namespace App\Repositories\TariffGroups;

use App\Models\TariffGroup;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class TariffGroupRepository extends BaseRepository implements ITariffGroupRepository
{
    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = TariffGroup::query();
        if (isset($search['suburb'])) {
            $query->where('suburb_id', $search['suburb']);
        }
        return $query->orderBy('suburb_id')->orderBy('min_size')->paginate($this->perPage);
    }

    public function getById(int $id): ?TariffGroup
    {
        $tariff = TariffGroup::query()->find($id);
        if (!$tariff) {
            throw new NotFoundResourceException('Tariff not found');
        }

        return $tariff;
    }

    /**
     * @throws ValidationException
     */
    public function create(array $attributes): ?TariffGroup
    {
        if ($this->exists($attributes['min_size'], $attributes['max_size'], $attributes['suburb_id'])) {
            throw ValidationException::withMessages(['code' => 'Tariff already exists']);
        }
        $tariff = new TariffGroup($attributes);
        $tariff->save();

        return $tariff->refresh();
    }

    /**
     * @throws ValidationException
     */
    public function update(int $id, array $attributes): ?TariffGroup
    {
        $tariff = $this->getById($id);
        if ($this->existsExcept($attributes['min_size'], $attributes['max_size'], $tariff->getAttribute('suburb_id'), $id)) {
            throw ValidationException::withMessages(['code' => 'Tariff property size overlaps with existing record']);
        }
        $tariff->fill($attributes);
        $tariff->save();

        return $tariff->refresh();
    }

    public function delete(int $id): ?TariffGroup
    {
        $tariff = $this->getById($id);
        $tariff->delete();

        return $tariff;
    }

    public function exists(float $min, float $max, int $suburbId): bool
    {
        return TariffGroup::query()
            ->where('suburb_id', $suburbId)
            ->where(function($query) use($min, $max) {
                $query->where(function($q) use($min, $max) {
                    $q->where('min_size', '>=', $min);
                    $q->where('max_size', '<=', $max);
                });
                $query->orWhere(function($query) use($min, $max) {
                    $query->where('max_size', '>=', $min);
                    $query->where('min_size', '<=', $max);
                });
            })->count() > 0;
    }

    public function existsExcept(float $min, float $max, int $suburbId, int $tariffId): bool
    {
        return TariffGroup::query()
            ->whereNot('id', $tariffId)
            ->where('suburb_id', $suburbId)
            ->where(function($query) use($min, $max) {
                $query->where(function($q) use($min, $max) {
                    $q->where('min_size', '>=', $min);
                    $q->where('max_size', '<=', $max);
                });
                $query->orWhere(function($query) use($min, $max) {
                    $query->where('max_size', '>=', $min);
                    $query->where('min_size', '<=', $max);
                });
            })->count() > 0;
    }

    public function getBySizeAndSuburb(float $size, int $suburbId): ?TariffGroup
    {
        return TariffGroup::query()
            ->where('suburb_id', $suburbId)
            ->where(function($query) use($size) {
                $query->where(function($q) use($size) {
                    $q->where('min_size', '>=', $size);
                    $q->where('max_size', '<=', $size);
                });
                $query->orWhere(function($query) use($size) {
                    $query->where('max_size', '>=', $size);
                    $query->where('min_size', '<=', $size);
                });
            })->first();
    }
}
