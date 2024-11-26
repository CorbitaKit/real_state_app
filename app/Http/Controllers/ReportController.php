<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user.personal_info')->where('status', 'Confirmed')->get();
        $clients = User::with('personal_info')->where('role_id', 3)->get();
        return Inertia::render('reports/index', [
            'payments' => $payments,
            'clients' => $clients
        ]);
    }

    public function filter(Request $request)
    {
        $start_date = Carbon::parse($request->date_from)->format('Y-m-d');
        $end_date = Carbon::parse($request->date_to)->format('Y-m-d');
        $client = $request->client;
        $payments = Payment::with('user.personal_info')->whereBetween('date_of_payment', [$start_date, $end_date])
        ->whereHas('user.personal_info', function ($query) use ($client) {
            $query->whereRaw("CONCAT(first_name, ' ', last_name) = ?", [$client]);
        })
        ->where('mode_of_payment', $request->mode_of_payment)
        ->get();
        return Inertia::render('reports/index', [
            'payments' => $payments
        ]);
    }
}
