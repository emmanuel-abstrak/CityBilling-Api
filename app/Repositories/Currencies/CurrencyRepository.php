<?php

namespace App\Repositories\Currencies;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

class CurrencyRepository implements ICurrencyRepository
{

    public function getAll(array $search = []): Collection
    {
        return Currency::query()->get();
    }

    public function getById($id): ?Currency
    {
        return Currency::query()->find($id);
    }

    public function create(array $attributes): ?Currency
    {
        $currency = new Currency($attributes);
        $currency->save();

        return $currency->refresh();
    }

    public function update($id, array $attributes): ?Currency
    {
        $currency = $this->getById($id);
        if (!is_object($currency)) return null;

        $currency->update($attributes);
        return $currency->refresh();
    }

    public function delete($id): ?Currency
    {
        $currency = $this->getById($id);
        if (!is_object($currency)) return null;

        $currency->delete();
        return $currency;
    }
}
