<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SecretaryPaymentsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $payments = Payment::with('student')
            ->latest()
            ->paginate(25);

        return response()->json([
            'data' => $payments->map(fn (Payment $payment) => [
                'id' => $payment->id,
                'student_name' => $payment->student?->name,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date?->toDateString(),
                'recorded_by' => $payment->recorded_by,
                'created_at' => $payment->created_at?->toDateString(),
            ]),
            'total' => $payments->total(),
            'per_page' => $payments->perPage(),
            'current_page' => $payments->currentPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'status' => ['required', 'in:paid,pending,late'],
            'payment_date' => ['nullable', 'date'],
        ]);

        $student = User::findOrFail($validated['student_id']);
        $this->assertStudent($student);

        DB::transaction(function () use ($validated, $student) {
            $payment = Payment::create([
                ...$validated,
                'recorded_by' => $request->user()->id,
                'payment_date' => $validated['payment_date'] ?? now(),
            ]);

            // Update student balance and status
            $this->applyBalanceDelta($student, 0, $payment->amount);
            $this->refreshStudentStatus($student);
        });

        return response()->json([
            'message' => 'Paiement enregistré avec succès',
        ], 201);
    }

    public function update(Request $request, Payment $payment): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:paid,pending,late'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['nullable', 'date'],
        ]);

        $oldAmount = $payment->amount;
        $newAmount = $validated['amount'];

        DB::transaction(function () use ($payment, $validated, $oldAmount, $newAmount) {
            $student = $payment->student;
            if ($student) {
                $this->applyBalanceDelta($student, $oldAmount, $newAmount);
                $this->refreshStudentStatus($student);
            }

            $payment->update($validated);
        });

        return response()->json([
            'message' => 'Paiement mis à jour avec succès',
        ]);
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $student = $payment->student;
        $amount = $payment->amount;

        DB::transaction(function () use ($payment, $student, $amount) {
            if ($student) {
                $this->applyBalanceDelta($student, $amount, 0);
                $this->refreshStudentStatus($student);
            }

            $payment->delete();
        });

        return response()->json([
            'message' => 'Paiement supprimé avec succès',
        ]);
    }

    private function assertStudent(User $user): void
    {
        if ($user->role !== 'student') {
            abort(422, 'L\'utilisateur sélectionné n\'est pas un étudiant.');
        }
    }

    private function applyBalanceDelta(User $student, float $oldAmount, float $newAmount): void
    {
        $delta = $newAmount - $oldAmount;
        $student->account_balance = max(0, $student->account_balance - $delta);
        $student->save();
    }

    private function refreshStudentStatus(User $student): void
    {
        if ($student->account_balance <= 0) {
            $student->payment_status = 'paid';
        } elseif ($student->account_balance < 1000) { // Assuming some threshold
            $student->payment_status = 'pending';
        } else {
            $student->payment_status = 'late';
        }
        $student->save();
    }
}