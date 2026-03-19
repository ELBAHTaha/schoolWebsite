<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreatedMail;
use App\Models\ProfessorWorkingHour;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ProfessorManagementController extends Controller
{
    public function index(): View
    {
        $professors = User::with('workingHours')
            ->where('role', 'professor')
            ->latest()
            ->paginate(20);

        return view('dashboard.secretary.professors.index', compact('professors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'working_hours.*.starts_at' => ['nullable', 'date_format:H:i'],
            'working_hours.*.ends_at' => ['nullable', 'date_format:H:i'],
        ]);

        $workingHours = $validated['working_hours'] ?? [];
        unset($validated['working_hours']);
        $plainPassword = $validated['password'];

        $professor = User::create([
            ...$validated,
            'role' => 'professor',
        ]);

        $slots = collect($workingHours)
            ->filter(fn ($slot) => !empty($slot['day']) && !empty($slot['starts_at']) && !empty($slot['ends_at']));

        foreach ($slots as $slot) {
            ProfessorWorkingHour::create([
                'professor_id' => $professor->id,
                'day_of_week' => $slot['day'],
                'starts_at' => $slot['starts_at'],
                'ends_at' => $slot['ends_at'],
            ]);
        }

        $loginUrl = rtrim(config('app.frontend_url'), '/') . '/login';
        Mail::to($professor->email)->send(new AccountCreatedMail($professor, $plainPassword, $loginUrl));

        return back()->with('status', 'Professeur créé avec succès.');
    }

    public function destroy(User $professor): RedirectResponse
    {
        abort_unless($professor->role === 'professor', 404);
        $professor->delete();

        return back()->with('status', 'Professeur supprimé.');
    }

    public function update(Request $request, User $professor): RedirectResponse
    {
        abort_unless($professor->role === 'professor', 404);

        $validated = $request->validate([
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'working_hours.*.starts_at' => ['nullable', 'date_format:H:i'],
            'working_hours.*.ends_at' => ['nullable', 'date_format:H:i'],
        ]);

        $professor->workingHours()->delete();

        $slots = collect($validated['working_hours'] ?? [])
            ->filter(fn ($slot) => !empty($slot['day']) && !empty($slot['starts_at']) && !empty($slot['ends_at']));

        foreach ($slots as $slot) {
            $professor->workingHours()->create([
                'day_of_week' => $slot['day'],
                'starts_at' => $slot['starts_at'],
                'ends_at' => $slot['ends_at'],
            ]);
        }

        return back()->with('status', 'Horaires professeur mis à jour.');
    }
}

