<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentsController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $limit = max(1, min(100, (int) $request->query('limit', 20)));

        $query = Payment::query()
            ->with(['student.schoolClass'])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $total = (clone $query)->count();
        $payments = $query->limit($limit)->get();

        $month = now()->month;
        $year = now()->year;

        $summary = [
            'total_collected' => (float) Payment::where('status', 'paid')
                ->where('month', $month)
                ->where('year', $year)
                ->sum('amount'),
            'pending' => (float) Payment::where('status', 'unpaid')->sum('amount'),
            'late' => (float) Payment::where('status', 'late')->sum('amount'),
            'pending_count' => Payment::where('status', 'unpaid')->count(),
            'late_count' => Payment::where('status', 'late')->count(),
        ];

        return response()->json([
            'summary' => $summary,
            'data' => $payments->map(fn (Payment $payment) => [
                'id' => $payment->id,
                'student' => $payment->student?->name,
                'course' => $payment->student?->schoolClass?->name,
                'amount' => (float) $payment->amount,
                'date' => optional($payment->created_at)->toDateString(),
                'status' => $payment->status,
            ]),
            'total' => $total,
        ]);
    }
}
