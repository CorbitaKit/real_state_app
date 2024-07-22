<?php

namespace App\Services;

use App\Repositories\LotGroupRepository;
use App\Services\LotService;

class LotGroupService extends Service
{
    protected $lotService;
    public function __construct(LotGroupRepository $repo, LotService $lotService,)
    {
        parent::__construct($repo);
        $this->lotService = $lotService;
    }

    public function storeLotGroups(array $lotGroups, int $propertyId, array $lots): void
    {
        foreach ($lotGroups as $lotGroup) {
            $lotGroup['color_label'] = $lotGroup['color_label']['color'];
            $lotGroup['property_id'] = $propertyId;
            $newLotGroup = parent::doCreate($lotGroup);

            $filteredLots = array_filter($lots, function ($lot) use ($newLotGroup) {
                return $lot['color_label'] === $newLotGroup->color_label;
            });
            $this->lotService->updateLots($filteredLots, $newLotGroup->id);
        }
    }
}
