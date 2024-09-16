<?php

namespace App\Repositories\Currencies;

use App\Models\Currency;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CurrencyRepository extends BaseRepository implements ICurrencyRepository
{

    public function getAll(array $search = []): LengthAwarePaginator
    {
        $query = Currency::query();
        if (isset($search['search'])) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('code', 'like', '%' . $search['search'] . '%');
                $q->orWhere('symbol', 'like', '%' . $search['search'] . '%');
            });
        }

        return $query->orderBy('code')->paginate($this->perPage);
    }

    public function getById($id): ?Currency
    {
        $currency = Currency::query()->find($id);
        if (!$currency) {
            throw new NotFoundResourceException('Currency not found');
        }

        return $currency;
    }

    public function create(array $attributes): ?Currency
    {
        $check = Currency::query()->where(['code' => $attributes['code'], 'symbol' => $attributes['symbol']])->first();
        if ($check) {
            throw ValidationException::withMessages(['code' => 'Currency already exists']);
        }

        $currency = new Currency($attributes);
        $currency->save();

        return $currency->refresh();
    }

    public function update($id, array $attributes): ?Currency
    {
        $currency = $this->getById($id);

        $currency->update($attributes);
        return $currency->refresh();
    }

    public function delete($id): ?Currency
    {
        $currency = $this->getById($id);

        $currency->delete();
        return $currency;
    }

    public function getByCode(string $code): ?Currency
    {
        $currency = Currency::query()->where('code', $code)->first();
        if (!$currency) {
            throw new NotFoundResourceException('Currency not found');
        }

        return $currency;
    }
}
