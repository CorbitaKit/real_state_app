<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Service implements BaseServiceInterface
{
    protected $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    public function doGet(): Collection
    {
        return $this->repo->get();
    }

    public function doFindById(int $id): Model
    {
        return $this->repo->findById($id);
    }

    public function doCreate(array $data): Model
    {
        return $this->repo->create($data);
    }

    public function doUpdate(array $data, int $id): Model
    {
        return $this->repo->update($data, $id);
    }

    public function doDestroy(int $id): bool
    {
        return $this->repo->delete($id);
    }
}
