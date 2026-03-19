<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    public function index(): View
    {
        $monthlyReports = Payment::query()
            ->select('year', 'month', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as payment_count'))
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        $years = Payment::query()->select('year')->distinct()->orderByDesc('year')->pluck('year');
        $months = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        // Optionally, you can add $stats if needed for the header cards
        // $stats = [...];

        return view('dashboard.admin.financial-reports.index', compact('monthlyReports', 'years', 'months'));
    }
}

