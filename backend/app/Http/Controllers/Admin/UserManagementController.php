<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreatedMail;
use App\Models\ProfessorWorkingHour;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->paginate(20);

        return view('dashboard.admin.users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,directeur,secretary,professor,student,visitor,commercial'],
            'phone' => ['nullable', 'string', 'max:30'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'working_hours.*.starts_at' => ['nullable', 'date_format:H:i'],
            'working_hours.*.ends_at' => ['nullable', 'date_format:H:i'],
        ]);

        $workingHours = $validated['working_hours'] ?? [];
        unset($validated['working_hours']);
        $plainPassword = $validated['password'];

        $user = User::create($validated);

        if ($user->role === 'professor') {
            $slots = collect($workingHours)
                ->filter(fn ($slot) => !empty($slot['day']) && !empty($slot['starts_at']) && !empty($slot['ends_at']));

            foreach ($slots as $slot) {
                ProfessorWorkingHour::create([
                    'professor_id' => $user->id,
                    'day_of_week' => $slot['day'],
                    'starts_at' => $slot['starts_at'],
                    'ends_at' => $slot['ends_at'],
                ]);
            }
        }

        $loginUrl = rtrim(config('app.frontend_url'), '/') . '/login';
        Mail::to($user->email)->send(new AccountCreatedMail($user, $plainPassword, $loginUrl));

        return back()->with('status', 'User created.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->user()?->role === 'directeur' && $user->role === 'admin') {
            return back()->with('error', "Un directeur ne peut pas supprimer un administrateur.");
        }

        $user->delete();

        return back()->with('status', 'User deleted.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->role !== 'professor') {
            return back()->with('error', 'Only professors have working hours.');
        }

        $validated = $request->validate([
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'working_hours.*.starts_at' => ['nullable', 'date_format:H:i'],
            'working_hours.*.ends_at' => ['nullable', 'date_format:H:i'],
        ]);

        $user->workingHours()->delete();

        $slots = collect($validated['working_hours'] ?? [])
            ->filter(fn ($slot) => !empty($slot['day']) && !empty($slot['starts_at']) && !empty($slot['ends_at']));

        foreach ($slots as $slot) {
            $user->workingHours()->create([
                'day_of_week' => $slot['day'],
                'starts_at' => $slot['starts_at'],
                'ends_at' => $slot['ends_at'],
            ]);
        }

        return back()->with('status', 'Horaires professeur mis à jour.');
    }
}

