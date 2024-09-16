<?php

namespace App\Repositories\Properties;

use App\Models\Property;
use App\Repositories\IBaseRepository;

interface IPropertyRepository extends IBaseRepository{
    public function getCurrentMonthBalanceTotal() : float;
    public function getByMeter(string $meter) : ?Property;
}
