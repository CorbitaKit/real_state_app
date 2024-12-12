<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\FileService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FileController extends Controller
{
    protected $fileService;
    use FileUploadTrait;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function viewUserDocuments()
    {
        return Inertia::render('user/documents');
    }

    public function uploadPropertyMap(Request $request)
    {
        $fileUpload = $request->file['value'];

        $path = $this->uploadFile($fileUpload, 'files/properties/map');
        $file = $this->fileService->doCreate([
            'filename' => $fileUpload->getClientOriginalName(),
            'url' => $path
        ]);

        $property = Property::where('id', $request->property_id)->first();
        $property->files()->save($file);
    }
}
