<?php

namespace App\Services;

use App\Repositories\AddressRepository;

class AddressService extends Service
{
    public function __construct(AddressRepository $repo)
    {
        parent::__construct($repo);
    }
}
