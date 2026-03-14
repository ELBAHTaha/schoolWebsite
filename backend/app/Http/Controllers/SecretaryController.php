<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\View\View;

class SecretaryController extends Controller
{
    public function index(): View
    {
        $stats = [
            'students' => User::where('role', 'student')->count(),
            'classes' => SchoolClass::count(),
            'pendingPayments' => Payment::whereIn('status', ['unpaid', 'late'])->count(),
            'paidThisMonth' => Payment::where('status', 'paid')
                ->where('month', (int) now()->format('n'))
                ->where('year', (int) now()->format('Y'))
                ->count(),
        ];

        return view('dashboard.secretary', compact('stats'));
    }
}
