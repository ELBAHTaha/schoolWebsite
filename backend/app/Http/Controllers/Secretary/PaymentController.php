<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::with('student')
            ->latest()
            ->paginate(25);

        return view('dashboard.secretary.payments.index', compact('payments'));
    }

    public function create(): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('dashboard.secretary.payments.create', compact('students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);
        $validated['status'] = $this->normalizeStatus($validated['status']);

        DB::transaction(function () use ($validated) {
            $student = User::lockForUpdate()->findOrFail($validated['student_id']);
            $this->assertStudent($student);

            $payment = Payment::create($validated);
            $this->applyBalanceDelta($student, 0, $this->paidAmount($payment));
            $this->refreshStudentStatus($student);
        });

        return redirect()->route('secretary.payments.index')->with('status', 'Paiement enregistre.');
    }

    public function show(Payment $payment): View
    {
        $payment->load('student');

        return view('dashboard.secretary.payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('dashboard.secretary.payments.edit', compact('payment', 'students'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $this->validatePayload($request, $payment->id);
        $validated['status'] = $this->normalizeStatus($validated['status']);

        DB::transaction(function () use ($validated, $payment) {
            $oldStudent = User::lockForUpdate()->findOrFail($payment->student_id);
            $this->assertStudent($oldStudent);
            $oldPaidAmount = $this->paidAmount($payment);

            $newStudent = $oldStudent;
            if ((int) $validated['student_id'] !== (int) $oldStudent->id) {
                $newStudent = User::lockForUpdate()->findOrFail($validated['student_id']);
                $this->assertStudent($newStudent);
            }

            $payment->update($validated);
            $newPaidAmount = $this->paidAmount($payment->fresh());

            if ($oldStudent->id === $newStudent->id) {
                $this->applyBalanceDelta($oldStudent, $oldPaidAmount, $newPaidAmount);
                $this->refreshStudentStatus($oldStudent);
            } else {
                $this->applyBalanceDelta($oldStudent, $oldPaidAmount, 0);
                $this->refreshStudentStatus($oldStudent);

                $this->applyBalanceDelta($newStudent, 0, $newPaidAmount);
                $this->refreshStudentStatus($newStudent);
            }
        });

        return redirect()->route('secretary.payments.show', $payment)->with('status', 'Paiement mis a jour.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        DB::transaction(function () use ($payment) {
            $student = User::lockForUpdate()->findOrFail($payment->student_id);
            $this->assertStudent($student);

            $oldPaidAmount = $this->paidAmount($payment);
            $payment->delete();

            $this->applyBalanceDelta($student, $oldPaidAmount, 0);
            $this->refreshStudentStatus($student);
        });

        return redirect()->route('secretary.payments.index')->with('status', 'Paiement supprime.');
    }

    private function validatePayload(Request $request, ?int $ignorePaymentId = null): array
    {
        return $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2020,2100'],
            'student_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('payments')->where(function ($query) use ($request) {
                    return $query->where('month', $request->input('month'))
                        ->where('year', $request->input('year'));
                })->ignore($ignorePaymentId),
            ],
            'status' => ['required', 'in:paid,pending,unpaid,late'],
            'payment_method' => ['required', 'in:cmi,cash'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function normalizeStatus(string $status): string
    {
        return $status === 'pending' ? 'unpaid' : $status;
    }

    private function paidAmount(Payment $payment): float
    {
        return $payment->status === 'paid' ? (float) $payment->amount : 0.0;
    }

    private function applyBalanceDelta(User $student, float $oldPaidAmount, float $newPaidAmount): void
    {
        $delta = $newPaidAmount - $oldPaidAmount;
        $nextBalance = max(0, (float) $student->account_balance - $delta);
        $student->account_balance = $nextBalance;
        $student->save();
    }

    private function refreshStudentStatus(User $student): void
    {
        $hasLate = $student->payments()->where('status', 'late')->exists();
        $hasPending = $student->payments()->whereIn('status', ['unpaid', 'late'])->exists();

        if ((float) $student->account_balance <= 0 && ! $hasPending) {
            $student->payment_status = 'paid';
        } elseif ($hasLate) {
            $student->payment_status = 'late';
        } else {
            $student->payment_status = 'pending';
        }

        $student->save();
    }

    private function assertStudent(User $user): void
    {
        abort_unless($user->role === 'student', 422, 'Selected user is not a student.');
    }
}
