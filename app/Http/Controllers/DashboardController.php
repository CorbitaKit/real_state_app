<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Application;
use App\Models\Lot;
use App\Models\Payment;
use App\Models\PaymentPlan;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentUser = User::with('personal_info')->where('id', $user->id)->first();

        if (!$currentUser->personal_info) {
            return redirect()->back();
        }
        $clients = User::with('personal_info', 'lots.payment', '')
        ->where('role_id', 3)
        ->whereHas('personal_info')
        ->get();
        $applications = Application::with('user.personal_info', 'lot.property')->get();
        $payments = Payment::with('user.personal_info', 'files', 'lots')->get();
        $properties = Property::with('lots', 'file')->get();

        // Overall Sales
        $overallSales = Payment::sum('amount');

        // Daily Sales
        $dailySales = Payment::selectRaw('DATE(created_at) as date, SUM(amount) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->total_sales];
            });

        // Weekly Sales
        $weeklySales = Payment::selectRaw('YEAR(created_at) as year, WEEK(created_at) as week, SUM(amount) as total_sales')
            ->groupBy('year', 'week')
            ->orderBy('year')
            ->orderBy('week')
            ->get()
            ->mapWithKeys(function ($item) {
                return ["{$item->year}-W{$item->week}" => $item->total_sales];
            });

        // Monthly Sales
        $monthlySales = Payment::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_sales')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return ["{$item->year}-{$item->month}" => $item->total_sales];
            });

        // Quarterly Sales
        $quarterlySales = Payment::selectRaw('YEAR(created_at) as year, QUARTER(created_at) as quarter, SUM(amount) as total_sales')
            ->groupBy('year', 'quarter')
            ->orderBy('year')
            ->orderBy('quarter')
            ->get()
            ->mapWithKeys(function ($item) {
                return ["{$item->year}-Q{$item->quarter}" => $item->total_sales];
            });

        // Yearly Sales
        $yearlySales = Payment::selectRaw('YEAR(created_at) as year, SUM(amount) as total_sales')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->mapWithKeys(function ($item) {
                return ["{$item->year}" => $item->total_sales];
            });

        // Combine all sales data into one array
        $sales = [
            'overall' => $overallSales,
            'daily' => $dailySales,
            'weekly' => $weeklySales,
            'monthly' => $monthlySales,
            'quarterly' => $quarterlySales,
            'yearly' => $yearlySales,
        ];

        $lotApplicationCount = Application::where('application_type', 'Lot Application')->count();
        $siteVisitCount = Application::where('application_type', 'Site Visit')->count();

        $approvedPaymentCount = Payment::where('status', 'Confirmed')->count();
        $pendingPaymentCount = Payment::where('status', 'Pending')->count();

        $availableLotCount = Lot::where('status', 'Available')->count();
        $pendingLotCount = Lot::where('status', 'Pending')->count();
        $occupiedLotCount = Lot::where('status', 'Occupied')->count();

        
        return Inertia::render('dashboard/index', [
            'clients' => $clients,
            'applications' => $applications,
            'payments' => $payments,
            'properties' => $properties,
            'sales' => $sales,
            'lot_application' => $lotApplicationCount,
            'site_visit' => $siteVisitCount,
            'approved_payment' => $approvedPaymentCount,
            'pending_payment' => $pendingPaymentCount,
            'available_lot' => $availableLotCount,
            'pending_lot' => $pendingLotCount,
            'occupied_lot' => $occupiedLotCount
        ]);
    }
}
