<?php

namespace App\Repositories;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Collection;

class LotRepository extends Repository
{
    public function __construct(Lot $lot)
    {
        parent::__construct($lot);
    }

    public function doFetchByProperty(int $propertyId): Collection
    {
        return $this->model->where('property_id', $propertyId)->get();
    }
}
