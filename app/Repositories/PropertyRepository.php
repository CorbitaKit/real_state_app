<?php

namespace App\Repositories;

use App\Models\Property;

class PropertyRepository extends Repository
{
    public function __construct(Property $property)
    {
        parent::__construct($property, ['lots', 'lotGroups', 'lots.lotGroup', 'file', 'lots.user.personal_info']);
    }
}
