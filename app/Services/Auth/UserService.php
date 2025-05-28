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
use App\Services\FileService;
use App\Traits\FileUploadTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Http;

class UserService extends Service
{
    use FileUploadTrait;
    protected $addressSerivce;
    protected $employeeService;
    protected $workService;
    protected $fileService;

    public function __construct(
        UserRepository $repo,
        AddressService $addressService,
        EmployeeService $employeeService,
        WorkDetailService $workDetailService,
        FileService $fileService
    ) {
        parent::__construct($repo);
        $this->addressSerivce = $addressService;
        $this->employeeService = $employeeService;
        $this->workService = $workDetailService;
        $this->fileService = $fileService;
    }
    public function doLogin(object $request): Object
    {
        if (Auth::attempt($request->all())) {
            $user = User::with('role')->where('id', Auth::user()->id)->first();

            //  try {
            //         $account_sid = config('app.twilio.sid');
            //         $auth_token = config('app.twilio.token');
            //         $twilio_number = config('app.twilio.number');

            //         $client = new \Twilio\Rest\Client($account_sid, $auth_token);

            //         $client->messages->create(
            //             "+639638805540", // Recipient's phone number
            //             [
            //                 'from' => $twilio_number,
            //                 'body' => 'Good day! This is from Aldebal Real State Services. We just want to inform you that you have an upcoming payment on April 17, 2025 amounting ₱2,500.',
            //             ]
            //         );
            //     } catch (\Exception $e) {
            //         dd($e->getMessage()); // Dump the error message for debugging
            //     }
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

                if ($convertedData['file']) {
                    $fileUpload = $data['file']['value'];
                    $path = $this->uploadFile($fileUpload, 'user/requirements');

                    $file = $this->fileService->doCreate([
                        'filename' => $fileUpload->getClientOriginalName(),
                        'url' => $path
                    ]);

                    $user->files()->save($file);
                }
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
