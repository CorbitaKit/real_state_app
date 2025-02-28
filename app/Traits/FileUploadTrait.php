<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    public function uploadFile(UploadedFile $file, $directory)
    {
        $path = $file->store($directory, 'public');
        return $path;
    }
}
