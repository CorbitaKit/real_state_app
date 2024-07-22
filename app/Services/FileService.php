<?php

namespace App\Services;

use App\Repositories\FileRepository;

class FileService extends Service
{
    public function __construct(FileRepository $fileRepository)
    {
        parent::__construct($fileRepository);
    }
}
