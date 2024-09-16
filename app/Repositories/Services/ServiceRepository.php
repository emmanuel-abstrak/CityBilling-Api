<?php

namespace App\Repositories\Services;

use App\Models\Service;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ServiceRepository extends BaseRepository implements IServiceRepository
{

    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = Service::query();
        if (isset($search['search'])) {
            $query->where('name', 'like', '%' . $search['search'] . '%');
        }

        return $query->orderBy('order')->paginate($this->perPage);
    }

    public function getById(int $id): ?Service
    {
        $service = Service::query()->find($id);
        if (!$service) {
            throw new NotFoundResourceException('Service not found');
        }

        return $service;
    }

    public function create(array $attributes): ?Service
    {
        $order = 1;
        $last = Service::query()->orderBy('order', 'desc')->first();
        if ($last) {
            $order = $last->getAttribute('order') + 1;
        }

        $attributes['order'] = $order;

        $service = new Service($attributes);
        $service->save();

        return $service->refresh();
    }

    public function update(int $id, array $attributes): ?Service
    {
        $service = $this->getById($id);
        $service->fill($attributes);
        $service->save();

        return $service->refresh();
    }

    public function delete(int $id): ?Service
    {
        $service = $this->getById($id);
        $service->delete();

        $services = Service::query()->orderBy('order')->get();

        foreach ($services as $index => $srv) {
            $srv->setAttribute('order', $index + 1);
            $srv->save();
        }

        return $service;
    }

    public function reorder(array $order): ?bool
    {
        foreach ($order as $index => $item) {
            $service = $this->getById($item);
            $service->setAttribute('order', $index + 1);
            $service->save();
        }

        return true;
    }
}
