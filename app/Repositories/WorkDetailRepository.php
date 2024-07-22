<?php

namespace App\Repositories;

use App\Models\WorkInformation;

class WorkDetailRepository extends Repository
{
    public function __construct(WorkInformation $workInformation)
    {
        parent::__construct($workInformation);
    }
}
