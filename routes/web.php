<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController as ClientController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', [LandingPageController::class, 'index']);
Route::get('view-property/{id}', [LandingPageController::class, 'show']);
Route::get('login', [UserController::class, 'index'])->name('login');
Route::post('login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'register']);
Route::post('/register', [UserController::class, 'doRegister']);

Route::get('/email/verify', function () {
    return Inertia::render('auth/verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();


    return redirect()->route('users.show', Auth::user()->id);
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('filter-properties/{filter}', [LandingPageController::class,'filterProperty']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('payments/get-by-user', [PaymentController::class, 'getUserPayment']);
    Route::post('payments/update/{id}', [PaymentController::class, 'update']);
    Route::get('lots/get-client-lots', [LotController::class, 'getClientLots']);
    Route::get('application/get-by-user', [ApplicationController::class, 'getUserApplication']);
    Route::get('users/create-client', [ClientController::class, 'createClient']);
    Route::get('clients', [ClientController::class, 'clients']);
    Route::resource('properties', PropertyController::class);
    Route::resource('users', ClientController::class);
    Route::resource('applications', ApplicationController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('reports', ReportController::class);
    Route::post('upload-file', [FileController::class, 'upload']);
    Route::patch('/update-personal-info/{user_id}', [ClientController::class, 'updatePersonalInfo']);
    Route::post('/reports/filter', [ReportController::class, 'filter']);
    Route::get('/documents', [FileController::class, 'viewUserDocuments']);
    Route::post('upload-property-map', [FileController::class,'uploadPropertyMap']);
    Route::get('/read-notification/{id}', [NotificationController::class, 'readNotification']);
    Route::get('/clear-notification', [NotificationController::class, 'clearNotification']);
    Route::post('logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    });

    Route::post('/upload-profile-picture', [UserController::class, 'uploadPicture']);

    Route::get('/notify-user/{user_id}', [UserController::class, 'notify']);
    Route::get('/lot-transfer/{user_id}/{lot_id}', [LotController::class, 'transferLot']);
    Route::get('/filter-clients/{filter}', [ClientController::class, 'filterClient']);

});
