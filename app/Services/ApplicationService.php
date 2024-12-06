<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Services\LotService;
use Carbon\Carbon;
use App\Models\PaymentPlan;
class ApplicationService extends Service
{
    protected $lotService;
    public function __construct(ApplicationRepository $repo, LotService $lotService)
    {
        parent::__construct($repo);
        $this->lotService = $lotService;
    }

    public function getByUser(): Collection
    {
        $userId = Auth::user()->id;
        return $this->repo->doGetByUser($userId);
    }

    public function doUpdate(array $data, int $id): Model
    {
        
        $application = parent::doUpdate($data, $id);
        if ($data['status'] === 'Rejected') {
            $this->lotService->doUpdate(['status' => 'Available'], $application->lot_id);
            return $application;
        } else {
            $this->lotService->doUpdate(['status' => 'Occupied'], $application->lot_id);
        }
        

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addYears(3);

        while ($startDate->lessThanOrEqualTo($endDate)) {
            $startDate->addMonth();
            $newPaymentPlan = new PaymentPlan();
            $newPaymentPlan->due_date = $startDate->copy();
            $newPaymentPlan->lot_id = $application->lot_id;
            $newPaymentPlan->user_id = $application->user_id;

            $newPaymentPlan->save();

            
        }

        return $application;
    }
}
