<?php

namespace App\Http\Controllers;

use App\Services\Auth\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        return Inertia::render('user/client');
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
}
