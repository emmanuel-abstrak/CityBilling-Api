<?php

namespace App\Repositories\Services;

use App\Repositories\IBaseRepository;

interface IServiceRepository extends IBaseRepository {

    public function reorder(array $order) : ?bool;
}
