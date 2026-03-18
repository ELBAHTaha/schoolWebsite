<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AdminPaymentsController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', 'in:paid,unpaid'],
            'payment_date' => ['nullable', 'date'],
            'months' => ['nullable', 'integer', 'min:1'],
        ]);

        $student = User::findOrFail($validated['student_id']);
        if ($student->role !== 'student') {
            abort(422, 'L\'utilisateur sélectionné n\'est pas un étudiant.');
        }

        $months = (int) ($validated['months'] ?? 1);
        $paymentDate = isset($validated['payment_date'])
            ? Carbon::parse($validated['payment_date'])
            : now();
        $perMonthAmount = round(((float) $validated['amount']) / max(1, $months), 2);
        $status = $validated['status'];
        $batchId = (string) Str::uuid();

        for ($i = 0; $i < $months; $i++) {
            $date = (clone $paymentDate)->addMonths($i);
            Payment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'month' => (int) $date->month,
                    'year' => (int) $date->year,
                ],
                [
                    'amount' => $perMonthAmount,
                    'status' => $status,
                    'transaction_id' => $batchId,
                ]
            );
        }

        $student->payment_status = $status === 'paid' ? 'paid' : 'pending';
        $student->save();

        return response()->json([
            'message' => 'Paiement enregistré avec succès',
        ], 201);
    }

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
