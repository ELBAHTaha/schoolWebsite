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

        $studentsQuery = User::query()
            ->where('role', 'student')
            ->with([
                'classes',
                'schoolClass',
                'payments' => fn ($q) => $q->orderByDesc('created_at'),
            ])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $total = (clone $studentsQuery)->count();
        $students = $studentsQuery->limit($limit)->get();

        $month = now()->month;
        $year = now()->year;
        $hasPayments = Payment::query()->exists();

        if ($hasPayments) {
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
        } else {
            $pending = 0.0;
            $late = 0.0;
            $totalCollected = 0.0;
            $pendingCount = 0;
            $lateCount = 0;

            foreach ($students as $student) {
                $amount = $this->resolveStudentAmount($student);
                $status = $this->normalizeStatus($student->payment_status);

                if ($status === 'paid') {
                    $totalCollected += $amount;
                } elseif ($status === 'late') {
                    $late += $amount;
                    $lateCount++;
                } else {
                    $pending += $amount;
                    $pendingCount++;
                }
            }

            $summary = [
                'total_collected' => $totalCollected,
                'pending' => $pending,
                'late' => $late,
                'pending_count' => $pendingCount,
                'late_count' => $lateCount,
            ];
        }

        return response()->json([
            'summary' => $summary,
            'data' => $students->map(function (User $student) {
                $latestPayment = $student->payments->first();
                $status = $latestPayment?->status ?? $this->normalizeStatus($student->payment_status);
                $amount = $latestPayment?->amount ?? $this->resolveStudentAmount($student);

                return [
                    'id' => $latestPayment?->id ?? $student->id,
                    'student_id' => $student->id,
                    'student' => $student->name,
                    'email' => $student->email,
                    'phone' => $student->phone,
                    'role' => $student->role,
                    'class_ids' => $student->classes->pluck('id')->values(),
                    'payment_status' => $student->payment_status,
                    'course' => $student->schoolClass?->name,
                    'amount' => (float) $amount,
                    'date' => optional($latestPayment?->created_at)->toDateString(),
                    'status' => $status,
                ];
            }),
            'total' => $total,
        ]);
    }

    private function normalizeStatus(?string $status): string
    {
        $value = strtolower(trim((string) $status));

        return match ($value) {
            'paid', 'paye', 'payé' => 'paid',
            'late', 'retard', 'en_retard', 'en retard' => 'late',
            'unpaid', 'pending', 'non_paye', 'non paye', 'non_payé', 'non payé' => 'unpaid',
            default => 'unpaid',
        };
    }

    private function resolveStudentAmount(User $student): float
    {
        $class = $student->schoolClass;
        $amount = $student->account_balance
            ?? $class?->price_1_month
            ?? $class?->price_3_month
            ?? $class?->price_6_month
            ?? 0;

        return (float) $amount;
    }
}
