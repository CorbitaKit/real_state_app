<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Auth\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;

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
        event(new Registered($user));
    }

    public function store(UserRequest $request)
    {
        return $this->userService->doCreate($request->all());
    }

    public function notify($user_id)
    {
        $user = User::with('personal_info')->where('id', $user_id)->first();
        $cleanedNumber = preg_replace('/[^0-9]/', '', $user->personal_info->phone_number);

        try {
            $account_sid = 'ACb9e10d5a08d249e1bd6d413679bab45f';
            $auth_token = '4b5f1fe7c0c3506be03a378e154c6f2f';
            $twilio_number = '+17433304054';

            $client = new \Twilio\Rest\Client($account_sid, $auth_token);

            $client->messages->create(
                $cleanedNumber, // Recipient's phone number
                [
                    'from' => $twilio_number,
                    'body' => 'Good day! This is from Aldebal Real State Services. We just want to inform you that your property is ready for transfer. Please bring the following requirements: One Valid ID and your proof of income.',
                ]
            );
        } catch (\Exception $e) {
            dd($e->getMessage()); // Dump the error message for debugging
        }

    }
}
