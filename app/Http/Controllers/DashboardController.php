<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Mechanic;
use App\Models\Vehicle;

class DashboardController extends Controller
{
    public function index()
    {
        $totalInvoices   = Invoice::count();
        $inProgress      = Invoice::where('status', 'in_progress')->count();
        $unpaid          = Invoice::whereIn('status', ['done', 'unpaid'])->count();
        $paid            = Invoice::where('status', 'paid')->count();
        $totalRevenue    = Invoice::where('status', 'paid')->sum('total');
        $totalVehicles   = Vehicle::count();
        $totalMechanics  = Mechanic::where('is_active', true)->count();

        $recentInvoices  = Invoice::with(['customer', 'vehicle', 'mechanic'])
            ->latest()->limit(10)->get();

        return view('dashboard.index', compact(
            'totalInvoices',
            'inProgress',
            'unpaid',
            'paid',
            'totalRevenue',
            'totalVehicles',
            'totalMechanics',
            'recentInvoices'
        ));
    }
}
