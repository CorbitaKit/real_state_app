<?php

namespace App\Services;

use App\Repositories\EmployeeRepository;

class EmployeeService extends Service
{
    public function __construct(EmployeeRepository $repo)
    {
        parent::__construct($repo);
    }
}
