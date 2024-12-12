<?php

namespace App\Repositories;

use App\Models\Property;

class PropertyRepository extends Repository
{
    public function __construct(Property $property)
    {
        parent::__construct($property, ['lots', 'lotGroups', 'lots.payments.user.personal_info',  'transactions.user.personal_info', 'lots.lotGroup', 'files', 'lots.user.personal_info', 'lots.property']);
    }
}
