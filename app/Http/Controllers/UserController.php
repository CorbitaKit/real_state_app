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
        $clients = User::with('personal_info', 'address', 'workDetails')->where('role_id', 3)->get();
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
