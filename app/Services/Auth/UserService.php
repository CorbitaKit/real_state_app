<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\AddressService;
use App\Services\EmployeeService;
use App\Services\WorkDetailService;

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
            $convertedData['personal_info']['address'] = 'custom address';
            $this->employeeService->doCreate($convertedData['personal_info']);
            $this->addressSerivce->doCreate($convertedData['personal_address']);
            $this->workService->doCreate($convertedData['work_details']);
        });
    }
}
