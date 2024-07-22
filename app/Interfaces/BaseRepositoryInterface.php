<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function get(): Collection;

    public function findById(int $id): Model;

    public function create(array $data): Model;

    public function update(array $data, int $id): Model;

    public function delete(int $id): bool;
}
