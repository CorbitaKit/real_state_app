<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\Auth\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return Inertia::render('auth/index');
    }
    public function login(Request $request)
    {
        return $this->userService->doLogin($request);
    }

    public function register()
    {
        return Inertia::render('auth/register');
    }

    public function doRegister(UserRequest $request)
    {
        $user = $this->userService->doCreate($request->all());
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('properties.index');
    }

    public function store(UserRequest $request)
    {
        return $this->userService->doCreate($request->all());
    }
}
