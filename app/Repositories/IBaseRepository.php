<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface IBaseRepository
{
    public function getAll(array $search = []) : LengthAwarePaginator;
    public function getById(int $id) : ?Model;
    public function create(array $attributes) : ?Model;
    public function update(int $id, array $attributes) : ?Model;
    public function delete(int $id) : ?Model;
}
