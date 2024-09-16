<?php

namespace App\Repositories\TariffGroups;

use App\Models\TariffGroup;
use App\Repositories\IBaseRepository;

interface ITariffGroupRepository extends IBaseRepository {
    public function exists(float $min, float $max, int $suburbId) : bool;
    public function existsExcept(float $min, float $max, int $suburbId, int $tariffId) : bool;
    public function getBySizeAndSuburb(float $size, int $suburbId) : ?TariffGroup;
}
