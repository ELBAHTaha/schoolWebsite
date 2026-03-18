<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreatedMail;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SecretaryStudentsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $classId = trim((string) $request->query('class_id', ''));

        $limit = max(1, min(200, (int) $request->query('limit', 20)));
        $query = User::with([
                'schoolClass',
                'classes',
                'payments' => fn ($q) => $q->latest()->limit(1),
            ])
            ->where('role', 'student');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($classId !== '') {
            $query->where('class_id', $classId);
        }

        $students = $query->latest()->paginate($limit);

        return response()->json([
            'data' => $students->map(fn (User $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'class_name' => $student->schoolClass?->name,
                'class_names' => $student->classes->pluck('name')->values(),
                'class_ids' => $student->classes->pluck('classes.id')->values(),
                'account_balance' => $student->account_balance,
                'payment_status' => $student->payment_status,
                'last_payment_amount' => $this->latestPaymentTotal($student),
                'last_payment_date' => $student->payments->first()?->created_at?->toDateString(),
                'status' => 'Actif', // Could be based on some logic
                'created_at' => $student->created_at?->toDateString(),
            ]),
            'total' => $students->total(),
            'per_page' => $students->perPage(),
            'current_page' => $students->currentPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
            'payment_status' => ['required', 'in:paid,pending,late'],
            'account_balance' => ['nullable', 'numeric', 'min:0'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
        ]);

        $amountPaid = (float) ($validated['amount_paid'] ?? 0);
        $accountBalance = (float) ($validated['account_balance'] ?? 0);
        $plainPassword = $validated['password'];
        $classIds = $validated['class_ids'] ?? [];
        unset($validated['amount_paid']);
        unset($validated['account_balance']);
        unset($validated['class_ids']);

        if (empty($validated['class_id']) && !empty($classIds)) {
            $validated['class_id'] = $classIds[0];
        }

        $student = User::create([
            ...$validated,
            'role' => 'student',
            'password' => Hash::make($plainPassword),
            'account_balance' => $accountBalance,
        ]);

        // Create payment record if amount was paid
        if ($amountPaid > 0) {
            $now = now();
            Payment::create([
                'student_id' => $student->id,
                'amount' => $amountPaid,
                'status' => 'paid',
                'month' => (int) $now->month,
                'year' => (int) $now->year,
                'transaction_id' => (string) \Illuminate\Support\Str::uuid(),
            ]);
        }

        if (!empty($classIds)) {
            $student->classes()->sync($classIds);
        }

        return response()->json([
            'message' => 'Étudiant créé avec succès',
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'class_name' => $student->schoolClass?->name,
                'class_names' => $student->classes()->pluck('name'),
                'class_ids' => $student->classes()->pluck('classes.id'),
                'account_balance' => $student->account_balance,
                'payment_status' => $student->payment_status,
            ],
        ], 201);
    }

    public function update(Request $request, User $student): JsonResponse
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
            'payment_status' => ['required', 'in:paid,pending,late'],
            'account_balance' => ['nullable', 'numeric', 'min:0'],
        ]);

        $classIds = $validated['class_ids'] ?? null;
        unset($validated['class_ids']);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'class_id' => $validated['class_id'] ?? null,
            'payment_status' => $validated['payment_status'],
        ];
        if (array_key_exists('account_balance', $validated)) {
            $updateData['account_balance'] = $validated['account_balance'];
        }

        if (empty($updateData['class_id']) && is_array($classIds) && !empty($classIds)) {
            $updateData['class_id'] = $classIds[0];
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $student->update($updateData);

        if (is_array($classIds)) {
            $student->classes()->sync($classIds);
        }

        return response()->json([
            'message' => 'Étudiant mis à jour avec succès',
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'class_name' => $student->schoolClass?->name,
                'class_names' => $student->classes()->pluck('name'),
                'class_ids' => $student->classes()->pluck('classes.id'),
                'account_balance' => $student->account_balance,
                'payment_status' => $student->payment_status,
            ],
        ]);
    }

    public function destroy(User $student): JsonResponse
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->delete();

        return response()->json([
            'message' => 'Étudiant supprimé avec succès',
        ]);
    }

    private function latestPaymentTotal(User $student): float
    {
        $latestPayment = $student->payments->first();
        if (!$latestPayment) {
            return 0.0;
        }
        $batchId = $latestPayment->transaction_id;
        if ($batchId) {
            return (float) Payment::where('student_id', $student->id)
                ->where('transaction_id', $batchId)
                ->sum('amount');
        }
        return (float) $latestPayment->amount;
    }
}
