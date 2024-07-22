<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController as ClientController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::inertia('/', 'auth/index')->middleware('redirect');
Route::post('login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'register']);
Route::post('/register', [UserController::class, 'doRegister']);

Route::get('/email/verify', function () {
    return Inertia::render('auth/verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/properties');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('login', [UserController::class, 'index'])->name('login')->middleware('redirect');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('payments/get-by-user', [PaymentController::class, 'getUserPayment']);
    Route::get('application/get-by-user', [ApplicationController::class, 'getUserApplication']);
    Route::resource('properties', PropertyController::class);
    Route::resource('users', ClientController::class);
    Route::resource('applications', ApplicationController::class);
    Route::resource('payments', PaymentController::class);




    Route::post('logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    });
});
