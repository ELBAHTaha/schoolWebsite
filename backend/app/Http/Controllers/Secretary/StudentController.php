<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = User::with('schoolClass')
            ->where('role', 'student')
            ->latest()
            ->paginate(20);

        return view('dashboard.secretary.students.index', compact('students'));
    }

    public function create(): View
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('dashboard.secretary.students.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
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
        unset($validated['amount_paid']);

        $student = User::create([
            ...$validated,
            'role' => 'student',
        ]);

        if ($amountPaid > 0) {
            Payment::create([
                'student_id' => $student->id,
                'amount' => $amountPaid,
                'month' => (int) now()->format('n'),
                'year' => (int) now()->format('Y'),
                'status' => 'paid',
                'payment_method' => 'cash',
            ]);

            $newBalance = max(0, (float) $student->account_balance - $amountPaid);
            $student->update(['account_balance' => $newBalance]);
        }

        $this->syncClassRelation($student);

        return redirect()->route('secretary.students.index')->with('status', 'Etudiant cree avec succes.');
    }

    public function show(User $student): View
    {
        $this->assertStudent($student);

        $student->load(['schoolClass', 'payments']);
        $totalPaid = (float) $student->payments()->where('status', 'paid')->sum('amount');
        $remainingBalance = (float) $student->account_balance;

        return view('dashboard.secretary.students.show', compact('student', 'totalPaid', 'remainingBalance'));
    }

    public function edit(User $student): View
    {
        $this->assertStudent($student);
        $classes = SchoolClass::orderBy('name')->get();

        return view('dashboard.secretary.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $this->assertStudent($student);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$student->id],
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'account_balance' => ['required', 'numeric', 'min:0'],
            'payment_status' => ['required', 'in:paid,pending,late'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $student->update($validated);
        $this->syncClassRelation($student);

        return redirect()->route('secretary.students.show', $student)->with('status', 'Etudiant mis a jour.');
    }

    private function assertStudent(User $student): void
    {
        abort_unless($student->role === 'student', 404);
    }

    private function syncClassRelation(User $student): void
    {
        if ($student->class_id) {
            $student->enrolledClasses()->sync([$student->class_id]);
            return;
        }

        $student->enrolledClasses()->detach();
    }
}
