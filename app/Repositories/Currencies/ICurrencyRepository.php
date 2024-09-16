<?php

namespace App\Repositories\Currencies;

use App\Models\Currency;
use App\Repositories\IBaseRepository;

interface ICurrencyRepository extends IBaseRepository {
    public function getByCode(string $code) : ?Currency;
}
