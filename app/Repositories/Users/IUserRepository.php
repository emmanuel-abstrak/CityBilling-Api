<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\IBaseRepository;

interface IUserRepository extends IBaseRepository {
    public function getByEmail(string $email) : ?User;
    public function changePassword(int $userId, string $password) : ?User;
    public function existsExcept(string $email, int $userId) : bool;
}
