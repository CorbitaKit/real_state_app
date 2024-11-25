<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\PaymentPlan;
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

        while (true) {
            // Query for the current month and year
            $paymentPlan = PaymentPlan::where('lot_id', $payment->lot_id)
                ->whereNull('payment_id')
                ->whereMonth('due_date', $currentDate->month)
                ->whereYear('due_date', $currentDate->year)
                ->first();
            dd($paymentPlan);
            // Break the loop if results are found
            if ($paymentPlans) {
                $paymentPlan->payment_id = $payment->id;
                $paymentPlan->save();
                break;

            }

            // Increment to the next month
            $currentDate->addMonth();
        }

        $currentDate = Carbon::now(); // Start with the current date
        $paymentPlans = null;

        

        $fileUpload = $data['file']['value'];
        $path = $this->uploadFile($fileUpload, 'files/payments');

        $file = $this->fileService->doCreate([
            'filename' => $fileUpload->getClientOriginalName(),
            'url' => $path
        ]);
        $payment->files()->save($file);
        return $payment;
    }

    public function doUpdate(array $data, $id): Model
    {
        $payment = parent::doUpdate($data, $id);
        $fileUpload = $data['file']['value'];
        $path = $this->uploadFile($fileUpload, 'files/payments/invoice');
        $file = $this->fileService->doCreate([
            'filename' => $fileUpload->getClientOriginalName(),
            'url' => $path
        ]);
        $payment->files()->save($file);
        return $payment;
    }
}
