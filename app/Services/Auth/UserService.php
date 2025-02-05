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
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Http;

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
            $user = User::with('role')->where('id', Auth::user()->id)->first();

            if ($user->role->name === 'Admin' || $user->role->name === 'Staff') {
                $overduePaymentPlans = PaymentPlan::with('user.personal_info', 'lot')
                ->where('due_date', '<', now()->format('Y-m-d'))
                ->whereNull('payment_id')
                ->where('is_sms_sent', 0)->get();


                foreach ($overduePaymentPlans as $overDue) {
                    $cleanedNumber = preg_replace('/[^0-9]/', '', $overDue->user->personal_info->phone_number);
          
                    $response = Http::withHeaders([
                        'Authorization' => 'App 37e306dbf911a4db4de9da34a281639d-5bc56cee-cb75-4e21-8915-1d9f0b41d091', // Replace YOUR_API_KEY with your actual key
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ])->post('https://e1p4v1.api.infobip.com/sms/2/text/advanced', [
                        'messages' => [
                            [
                                'destinations' => [['to' => $cleanedNumber]],
                                'from' => '447491163443',
                                'text' => 'Good day! this is from Aldebal Real State Services. we just want to inform you that you missed your payment for ' . $overDue->due_date
                            ]
                        ]
                    ]);
            
                    if ($response->successful()) {
                        $overDue->is_sms_sent = 1;
                        $overDue->save();
                    } else {
                        dd($response->status());
                    }
                }

                
            }
            $request->session()->regenerate();
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
                $user->email_verified_at = now();
                $user->save();
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
