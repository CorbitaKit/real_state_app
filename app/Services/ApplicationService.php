<?php

namespace App\Services;

use App\Models\Lot;
use App\Models\Notification;
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
            $this->lotService->doUpdate(['status' => 'Available', 'user_id' => null], $application->lot_id);
            Notification::create([
                'text' => 'Your application has been rejected',
                'type' => 'application',
                'is_read' => 0,
                'is_admin' => 0,
                'user_id' => $application->user_id,
            ]);
            return $application;
        } else {
            $this->lotService->doUpdate(['status' => 'Occupied'], $application->lot_id);
            Notification::create([
                'text' => 'Your application has been approved',
                'type' => 'application',
                'is_read' => 0,
                'is_admin' => 0,
                'user_id' => $application->user_id,
            ]);
        }

        $lot = Lot::with('property')->where('id', $application->lot_id)->first();

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonth((int)$lot->property->balance_payable);





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
