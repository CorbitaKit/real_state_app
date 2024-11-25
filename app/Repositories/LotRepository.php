<?php

namespace App\Repositories;

use App\Models\Lot;
use Illuminate\Database\Eloquent\Collection;

class LotRepository extends Repository
{
    protected $relations = ['user', 'property', 'lotGroup', 'payments'];
    public function __construct(Lot $lot)
    {
        parent::__construct($lot);
    }

    public function doFetchByProperty(int $propertyId): Collection
    {
        return $this->model->where('property_id', $propertyId)->get();
    }

    public function doFetchClientLots(int $clientId): Collection
    {
        return $this->model->where('user_id', $clientId)->with(['user', 'property', 'lotGroup', 'payments', 'paymentPlans.payment', 'paymentPlans.lot.lotGroup'])->get();
    }
}
