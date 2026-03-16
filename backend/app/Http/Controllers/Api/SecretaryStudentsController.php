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

        $query = User::with('schoolClass')
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

        $students = $query->latest()->paginate(20);

        $loginUrl = rtrim(config('app.frontend_url'), '/') . '/login';
        Mail::to($student->email)->send(new AccountCreatedMail($student, $plainPassword, $loginUrl));

        return response()->json([
            'data' => $students->map(fn (User $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'class_name' => $student->schoolClass?->name,
                'account_balance' => $student->account_balance,
                'payment_status' => $student->payment_status,
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
            'account_balance' => ['required', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:paid,pending,late'],
            'amount_paid' => ['nullable', 'numeric', 'min:0'],
        ]);

        $amountPaid = (float) ($validated['amount_paid'] ?? 0);
        $plainPassword = $validated['password'];
        unset($validated['amount_paid']);

        $student = User::create([
            ...$validated,
            'role' => 'student',
            'password' => Hash::make($plainPassword),
        ]);

        // Create payment record if amount was paid
        if ($amountPaid > 0) {
            Payment::create([
                'student_id' => $student->id,
                'amount' => $amountPaid,
                'status' => 'paid',
                'payment_date' => now(),
                'recorded_by' => $request->user()->id,
            ]);
        }

        return response()->json([
            'message' => 'Étudiant créé avec succès',
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'class_name' => $student->schoolClass?->name,
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
            'account_balance' => ['required', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:paid,pending,late'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'class_id' => $validated['class_id'],
            'account_balance' => $validated['account_balance'],
            'payment_status' => $validated['payment_status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $student->update($updateData);

        return response()->json([
            'message' => 'Étudiant mis à jour avec succès',
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'phone' => $student->phone,
                'class_name' => $student->schoolClass?->name,
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
}
