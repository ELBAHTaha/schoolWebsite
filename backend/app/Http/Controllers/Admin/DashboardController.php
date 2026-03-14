<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $monthlyRevenue = Payment::where('status', 'paid')
            ->where('month', (int) now()->format('n'))
            ->where('year', (int) now()->format('Y'))
            ->sum('amount');

        $stats = [
            'total_students' => User::where('role', 'student')->count(),
            'monthly_revenue' => number_format((float) $monthlyRevenue, 2),
            'payments_received' => Payment::where('status', 'paid')->count(),
            'global_users' => User::count(),
            'global_classes' => SchoolClass::count(),
        ];

        return view('dashboard.admin.index', compact('stats'));
    }
}
