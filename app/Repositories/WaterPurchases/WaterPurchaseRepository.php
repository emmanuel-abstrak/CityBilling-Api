<?php

namespace App\Repositories\WaterPurchases;

use App\Models\WaterPurchase;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class WaterPurchaseRepository extends BaseRepository implements IWaterPurchaseRepository
{
    public function getAll(array $search = []): LengthAwarePaginator
    {
        return WaterPurchase::query()->paginate($this->perPage);
    }

    public function getById(int $id): ?WaterPurchase
    {
        $purchase = WaterPurchase::query()->find($id);
        if (!is_object($purchase)) {
            throw new NotFoundResourceException('User not found');
        }

        return $purchase;
    }

    public function create(array $attributes): ?WaterPurchase
    {
        $purchase = new WaterPurchase($attributes);
        $purchase->save();
        return $purchase;
    }

    public function update(int $id, array $attributes): ?WaterPurchase
    {
        $purchase = $this->getById($id);
        $purchase->update($attributes);
        return $purchase->refresh();
    }

    public function delete(int $id): ?WaterPurchase
    {
        $purchase = $this->getById($id);
        $purchase->delete();
        return $purchase;
    }
}
