<?php

namespace App\Repositories;

use App\Models\LotGroup;

class LotGroupRepository extends Repository
{
    public function __construct(LotGroup $lotGroup)
    {
        parent::__construct($lotGroup);
    }
}
