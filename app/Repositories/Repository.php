<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class Repository implements BaseRepositoryInterface
{
    protected $model;
    protected $relations;
    public function __construct(Model $model, $relations = [])
    {
        $this->model = $model;
        $this->relations = $relations;
    }

    public function get(): Collection
    {
        return $this->model->with($this->relations)->get();
    }

    public function findById(int $id): Model
    {
        return $this->model->with($this->relations)->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data, int $id): Model
    {
        $model = $this->model->with($this->relations)->findOrFail($id);

        $model->update($data);

        return $model;
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
