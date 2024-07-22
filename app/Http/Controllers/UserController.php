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
    public function show($id)
    {
        return Inertia::render('user/profile', ['user_id'  => (int)$id]);
    }

    public function store(Request $request)
    {
        return $this->service->doStore($request);
    }
}
