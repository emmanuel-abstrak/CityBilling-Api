<?php

namespace App\Repositories\Users;

use App\Repositories\IBaseRepository;
use Illuminate\Database\Eloquent\Model;

interface IUserRepository extends IBaseRepository {
    public function getByEmail(string $email) : ?Model;
}
