<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentController extends Controller
{
    protected $service;

    public function __construct(PaymentService $paymentService)
    {
        $this->service = $paymentService;
    }

    public function index()
    {
        return Inertia::render('payment/index', [
            'payments' => $this->service->doGet()
        ]);
    }
    public function create()
    {
        return Inertia::render('payment/create', ['lots' => Auth::user()->lots]);
    }

    public function store(Request $request)
    {
        $this->service->doCreate($request->all());
    }

    public function update(Request $request, $id)
    {
        $this->service->doUpdate($request->all(), (int)$id);
    }

    public function getUserPayment()
    {
        return Inertia::render('payment/index', [
            'payments' => $this->service->userPayment()
        ]);
    }
}
