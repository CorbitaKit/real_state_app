<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository extends Repository
{
    public function __construct(File $file)
    {
        parent::__construct($file);
    }
}
