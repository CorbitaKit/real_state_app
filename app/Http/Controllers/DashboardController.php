<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Application;
use App\Models\Payment;
use App\Models\Property;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = User::with('personal_info')
        ->where('role_id', 3)
        ->whereHas('personal_info')
        ->get();
        $applications = Application::with('user.personal_info', 'lot.property')->get();
        $payments = Payment::get()->count();
        $properties = Property::get()->count();

        $salesPerQuarter = Payment::selectRaw('YEAR(created_at) as year, QUARTER(created_at) as quarter, SUM(amount) as total_sales')
            ->groupBy('year', 'quarter')
            ->orderBy('year')
            ->orderBy('quarter')
            ->get();
                
        return Inertia::render('dashboard/index', [
            'clients' => $clients,
            'applications' => $applications,
            'payments' => $payments,
            'properties' => $properties,
            'sales' => $salesPerQuarter
        ]);
    }
}
