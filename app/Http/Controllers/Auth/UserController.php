<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\File;
use App\Models\User;
use App\Services\Auth\UserService;
use App\Services\FileService;
use App\Traits\FileUploadTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;

class UserController extends Controller
{
    protected $userService;
    use FileUploadTrait;
    protected $fileService;
    public function __construct(UserService $userService, FileService $fileService)
    {
        $this->userService = $userService;
        $this->fileService = $fileService;
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
            $account_sid = 'ACd7c9236c417ab9a47ba9052300950307';
            $auth_token = '9020711417f033be5e61ed9359298187';
            $twilio_number = '+12602354531';

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

    public function uploadPicture(Request $request)
    {
        $fileUpload = $request->file['value'];
        $path = $this->uploadFile($fileUpload, 'files/user/profile');
        $fileExist = File::where('fileable_id', $request->user_id)->where('type', 'profile_picture')->first();
        if($fileExist) {
            File::findOrFail($fileExist->id)->delete();
        }


        $file = $this->fileService->doCreate([
            'filename' => $fileUpload->getClientOriginalName(),
            'url' => $path,
            'type' => 'profile_picture'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->files()->save($file);
    }
}
