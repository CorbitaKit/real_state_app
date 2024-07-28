<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LotService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LotController extends Controller
{
    protected $lotService;

    public function __construct(LotService $lotService)
    {
        $this->lotService = $lotService;
    }

    public function getClientLots()
    {
        return Inertia::render('user/properties', [
            'client_lots' => $this->lotService->doGetClientLots(Auth::user()->id)
        ]);
    }
}
