<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    public function getAll(array $search = []) : Collection;
    public function getById(int $id) : ?Model;
    public function create(array $attributes) : ?Model;
    public function update(int $id, array $attributes) : ?Model;
    public function delete(int $id) : ?Model;
}
