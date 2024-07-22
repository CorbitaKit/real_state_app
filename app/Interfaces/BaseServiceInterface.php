<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseServiceInterface
{
    public function doGet(): Collection;

    public function doFindById(int $id): Model;

    public function doCreate(array $data): Model;

    public function doUpdate(array $data, int $id): Model;

    public function doDestroy(int $id): bool;
}
