<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Lot;
use App\Models\Notification;
use App\Repositories\ApplicationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Services\LotService;
use Carbon\Carbon;
use App\Models\PaymentPlan;
use Twilio\Rest\Client;

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

        // $this->sendMessage($application->id);

        return $application;
    }

    protected function sendMessage(int $application_id)
    {
        $application = Application::with('user.personal_info', 'lot.property')->where('id', $application_id)->first();


        $message = "Good Day! Mr./Ms. " . $application->user->personal_info->first_name . ", this is from Aldebal Realty Service. We would like to inform you that your application for lot at ".
        " Phase " . $application->lot->property->phase .", Block ".  $application->lot->block . ", " . $application->lot->name . ", Purok " . $application->lot->property->purok . ", Barangay " .
        $application->lot->property->barangay ." ". $application->lot->property->city ." City, ". $application->lot->property->province . " has been approved.". " Please bring 1 valid ID. Thank you.";

        try {
            $account_id = "ACb9e10d5a08d249e1bd6d413679bab45f";
            $auth_token = "4b5f1fe7c0c3506be03a378e154c6f2f";
            $twilio_number ="+17433304054";

            $client = new Client($account_id, $auth_token);

            $client->messages->create($application->user->personal_info->phone_number, [
                "from" => $twilio_number, "body" => $message
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
