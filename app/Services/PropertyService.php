<?php

namespace App\Services;

use App\Repositories\PropertyRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Services\LotGroupService;
use App\Services\LotService;

class PropertyService extends Service
{
    use FileUploadTrait;
    protected $fileService;
    protected $lotGroupService;

    protected $lotService;

    public function __construct(
        PropertyRepository $repo,
        FileService $fileService,
        LotGroupService $lotGroupService,
        LotService $lotService
    ) {
        parent::__construct($repo);
        $this->fileService = $fileService;
        $this->lotService = $lotService;
        $this->lotGroupService = $lotGroupService;
    }

    public function doStore(object $data): Model
    {
        return DB::transaction(function () use ($data) {
            $property = parent::doCreate($data->all());
            $fileUpload = $data->file['value'];
            $path = $this->uploadFile($fileUpload, 'files/properties');
            $file = $this->fileService->doCreate([
                'filename' => $fileUpload->getClientOriginalName(),
                'url' => $path
            ]);
            $property->files()->save($file);
            $lots = $this->lotService->storeLots($data->lots, $property->id);
            $this->lotGroupService->storeLotGroups($data->lot_groups, $property->id, $lots->toArray());
            return $property;
        });
    }
}
