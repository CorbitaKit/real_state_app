<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentService extends Service
{
    use FileUploadTrait;

    protected $fileService;
    public function __construct(PaymentRepository $repo, FileService $fileService)
    {
        parent::__construct($repo);
        $this->fileService = $fileService;
    }

    public function userPayment(): Collection
    {
        return $this->repo->getUserPayment(Auth::user()->id);
    }

    public function doCreate(array $data): Model
    {
        $payment = parent::doCreate($data);

        $fileUpload = $data['file']['value'];
        $path = $this->uploadFile($fileUpload, 'files/payments');

        $file = $this->fileService->doCreate([
            'filename' => $fileUpload->getClientOriginalName(),
            'url' => $path
        ]);
        $payment->file()->save($file);
        return $payment;
    }
}
