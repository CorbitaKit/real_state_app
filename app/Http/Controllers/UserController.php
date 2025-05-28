<?php

namespace App\Http\Controllers;

use App\Services\Auth\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Employee;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function index()
    {
        $staffs = $this->service->getStaffs();
        return Inertia::render('user/index', [
            'staffs' => $staffs
        ]);
    }

    public function filterClient(String $filter)
    {
        $clients = User::with([
            'personal_info',
            'address',
            'workDetails',
            'payments.plan.lot.property',
            'lots.property',
            'lots.lotGroup',
            'lots.paymentPlans.payment',
            'lots.payments',
            'lots.paymentPlans.lot.lotGroup',
            'lots.user.personal_info'
        ])
        ->where('role_id', 3)
        ->where(function ($query) use ($filter) {
            $query->where('email', 'like', '%' . $filter . '%')
                  ->orWhereHas('personal_info', function ($query) use ($filter) {
                      $query->where('first_name', 'like', '%' . $filter . '%')
                            ->orWhere('last_name', 'like', '%' . $filter . '%')
                            ->orWhere('phone_number', 'like', '%' . $filter . '%');
                  });
        })
        ->get()
        ->map(function ($user) {
            $userHasEightyPercentLot = false; // Track if any lot has 80% payment

            // Iterate over each lot
            $user->lots->map(function ($lot) use (&$userHasEightyPercentLot) {
                $lotTotalPaid = $lot->payments->sum('amount');
                $lotTotalPrice = ($lot->lotGroup)
                    ? ($lot->lotGroup->sqr_meter * $lot->lotGroup->amount_per_sqr_meter)
                    : 0;

                // Calculate lot's payment percentage
                $lotPaymentPercentage = $lotTotalPrice > 0 ? round(($lotTotalPaid / $lotTotalPrice) * 100, 2) : 0;

                // Set attributes for lot
                $lot->setAttribute('payment_percentage', $lotPaymentPercentage);

                // If any lot is 80% paid, mark user as true
                if ($lotPaymentPercentage >= 80) {
                    $userHasEightyPercentLot = true;
                }

                return $lot;
            });

            // Set user's is_eighty_percent based on any lot reaching 80%
            $user->setAttribute('is_eighty_percent', $userHasEightyPercentLot);

            return $user;
        });

        return response(json_encode($clients), 200);
    }

    public function create()
    {
        return Inertia::render('user/create', ['is_client' => false]);
    }

    public function createClient()
    {
        return Inertia::render('user/create', ['is_client' => true]);
    }

    public function clients()
    {

        $clients = User::with('personal_info',
        'address',
        'workDetails',
        'payments.plan.lot.property',
        'lots.property',
        'lots.lotGroup',
        'lots.paymentPlans.payment',
        'lots.payments',
        'lots.paymentPlans.lot.lotGroup',
        'lots.user.personal_info')
        ->where('role_id', 3)
        ->get()
        ->map(function ($user) {
            $userHasEightyPercentLot = false; // Track if any lot has 80% payment

            // Iterate over each lot
            $user->lots->map(function ($lot) use (&$userHasEightyPercentLot) {
                $lotTotalPaid = $lot->payments->sum('amount');
                $lotTotalPrice = ($lot->lotGroup)
                    ? ($lot->lotGroup->sqr_meter * $lot->lotGroup->amount_per_sqr_meter)
                    : 0;

                // Calculate lot's payment percentage
                $lotPaymentPercentage = $lotTotalPrice > 0 ? round(($lotTotalPaid / $lotTotalPrice) * 100, 2) : 0;

                // Set attributes for lot
                $lot->setAttribute('payment_percentage', $lotPaymentPercentage);

                // If any lot is 80% paid, mark user as true
                if ($lotPaymentPercentage >= 80) {
                    $userHasEightyPercentLot = true;
                }

                return $lot;
            });

            // Set user's is_eighty_percent based on any lot reaching 80%
            $user->setAttribute('is_eighty_percent', $userHasEightyPercentLot);

            return $user;
        });
        return Inertia::render('user/client', [
            'clients' => $clients
        ]);
    }
    public function show($id)
    {
        $user = $this->service->doFindById((int)$id);

        if ($user->personal_info) {
            return Inertia::render('user/view', ['userInfo' => $this->service->doFindById((int)$id)]);
        } else {
            return Inertia::render('user/profile', ['user_id' => ($id)]);
        }
    }
    public function store(Request $request)
    {
        return $this->service->doStore($request);
    }

    public function destroy($id)
    {
        $this->service->doDestroy($id);
        $this->index();
    }

    public function updatePersonalInfo(Request $request, $user_id)
    {
        $personal_info = Employee::where('user_id', $user_id)->first();

        $personal_info->update($request->all());
        $this->show($user_id);
    }


}
