<?php

namespace App\Services;

use App\Repositories\LotRepository;
use Illuminate\Database\Eloquent\Collection;

class LotService extends Service
{
    public function __construct(LotRepository $repo)
    {
        parent::__construct($repo);
    }

    public function fetchLotByProperty(int $propertyId): Collection
    {
        return $this->repo->doFetchByProperty($propertyId);
    }

    public function storeLots(array $lots, int $propertyId): Collection
    {
        foreach ($lots as $lot) {
            parent::doCreate([
                'color_label' => $lot['color_label'],
                'status' => $lot['status'],
                'property_id' => $propertyId,
                'name' => $lot['name'],
                'block' => $lot['block']
            ]);
        }

        return self::fetchLotByProperty($propertyId);
    }

    public function updateLots(array $lots, int $lotGroupId): void
    {
        foreach ($lots as $lot) {
            parent::doUpdate(['lot_group_id' => $lotGroupId], $lot['id']);
        }
    }

    public function doGetClientLots(int $clientId): Collection
    {
        return $this->repo->doFetchClientLots($clientId);
    }
}
