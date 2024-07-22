<?php

namespace App\Services;

use App\Repositories\WorkDetailRepository;

class WorkDetailService extends Service
{
    public function __construct(WorkDetailRepository $repo)
    {
        parent::__construct($repo);
    }
}
