<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $currentMonth = (int) now()->format('n');
        $currentYear = (int) now()->format('Y');

        $stats = [
            'total_payments' => Payment::count(),
            'paid_this_month' => Payment::where('status', 'paid')
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->count(),
            'pending_payments' => Payment::whereIn('status', ['unpaid', 'late'])->count(),
            'collected_this_month' => number_format((float) Payment::where('status', 'paid')
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->sum('amount'), 2),
        ];

        return view('dashboard.secretary.index', compact('stats'));
    }
}
