<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\User;
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
            'client_lots' => $this->lotService->doGetClientLots(Auth::user()->id),
            'users' => User::with('personal_info')->where('role_id', 3)->get()
        ]);
    }

    public function transferLot($user_id, $lot_id)
    {
        $lot = Lot::findOrFail($lot_id);
        $lot->update(['user_id' =>  $user_id]);

        return response(json_encode('Success', 200));
    }
}
