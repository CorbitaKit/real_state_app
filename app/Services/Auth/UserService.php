<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\AddressService;
use App\Services\EmployeeService;
use App\Services\WorkDetailService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use App\Models\PaymentPlan;
use Twilio\Rest\Client;

class UserService extends Service
{
    protected $addressSerivce;
    protected $employeeService;
    protected $workService;

    public function __construct(
        UserRepository $repo,
        AddressService $addressService,
        EmployeeService $employeeService,
        WorkDetailService $workDetailService
    ) {
        parent::__construct($repo);
        $this->addressSerivce = $addressService;
        $this->employeeService = $employeeService;
        $this->workService = $workDetailService;
    }
    public function doLogin(object $request): Object
    {
        if (Auth::attempt($request->all())) {
            $request->session()->regenerate();

            $user = User::with('role')->where('id',  Auth::user()->id)->first();

            // if ($user->role->name === 'Admin' || $user->role->name === 'Staff') {
            //     dd(env("TWILIO_AUTH_TOKEN"));
            //      // Twilio client initialization
            //     $account_sid = env("TWILIO_SID");
            //     $auth_token = env("TWILIO_AUTH_TOKEN");
            //     $twilio_number = env("TWILIO_PHONE_NUMBER");
            //     $client = new Client($account_sid, $auth_token);
            //     $dueDate = Carbon::now()->format('Y-m-d');
                
            //     $duePayments = PaymentPlan::with('lot.user.personal_info')->where('due_date', $dueDate)
            //     ->where('is_sms_sent', 0)->get();

            
              
            //     foreach ($duePayments as $duePayment) {
            //         $message = "Reminder: Payment for today is due. Amount: " . $duePayment->amount;

            //         try {
            //             $client->messages->create(
            //                 '09511046579', // Recipient's phone number
            //                 [
            //                     'from' => $twilio_number,
            //                     'body' => $message,
            //                 ]
            //             );

            //             dd("SEND SUCCESSFULLY");
            //         } catch (\Exception $e) {
            //             dd($e->getMessage());
            //             \Log::error("Failed to send SMS to {$duePayment->lot->user->personal_info->phone_number}: " . $e->getMessage());
            //         }
            //     }
            // }
            
            
            return back()->with(['message' => 'Authorized', 'user' => parent::doFindById(Auth::user()->id)]);
        }

        return back()->withErrors([
            'error' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function doStore(object $data)
    {
        return DB::transaction(function () use ($data) {

            $convertedData = $data->all();
            if (!isset($convertedData['user_id'])) {
                $user = $this->doCreate($convertedData['account_info']);
                $convertedData['personal_info']['user_id'] = $user->id;
                $convertedData['personal_address']['user_id'] = $user->id;
                $convertedData['work_details']['user_id'] = $user->id;
            }
            $convertedData['personal_info']['address'] = 'custom address';
            $this->employeeService->doCreate($convertedData['personal_info']);
            $this->addressSerivce->doCreate($convertedData['personal_address']);
            $this->workService->doCreate($convertedData['work_details']);
            
        });
    }

    public function getStaffs(): Collection
    {
        return $this->repo->getStaffs();
    }
}
