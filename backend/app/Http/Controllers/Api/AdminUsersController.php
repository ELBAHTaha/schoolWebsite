<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Mail\AccountCreatedMail;
use App\Models\ProfessorWorkingHour;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = trim((string) $request->query('search', ''));
        $role = trim((string) $request->query('role', ''));
        $limit = max(1, min(100, (int) $request->query('limit', 20)));

        $query = User::query()->orderByDesc('created_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        $total = (clone $query)->count();

        $users = $query->limit($limit)->get();

        return response()->json([
            'data' => $users->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->role === 'visitor' ? 'inactive' : 'active',
                'created_at' => optional($user->created_at)->toDateString(),
            ]),
            'total' => $total,
        ]);
    }

    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $workingHours = $validated['working_hours'] ?? [];
        unset($validated['working_hours']);
        $plainPassword = $validated['password'];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($plainPassword),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);
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

        return response()->json([
            'message' => 'Utilisateur crÃ©Ã© avec succÃ¨s',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->role === 'visitor' ? 'inactive' : 'active',
                'created_at' => $user->created_at?->toDateString(),
            ],
        ], 201);
    }

    public function update(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        $allowedRoles = [
            'admin',
            'directeur',
            'secretary',
            'professor',
            'student',
            'visitor',
            'commercial',
        ];

        if (!$request->user()?->hasRole('admin')) {
            $allowedRoles = ['professor', 'student', 'visitor'];
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in($allowedRoles)],
            'phone' => ['nullable', 'string', 'max:30'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'working_hours.*.starts_at' => ['nullable', 'date_format:H:i'],
            'working_hours.*.ends_at' => ['nullable', 'date_format:H:i'],
        ]);

        $workingHours = $validated['working_hours'] ?? null;
        unset($validated['working_hours']);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($validated['role'] !== 'professor') {
            $user->workingHours()->delete();
        } elseif (is_array($workingHours)) {
            $user->workingHours()->delete();
            $slots = collect($workingHours)
                ->filter(fn ($slot) => !empty($slot['day']) && !empty($slot['starts_at']) && !empty($slot['ends_at']));

            foreach ($slots as $slot) {
                $user->workingHours()->create([
                    'day_of_week' => $slot['day'],
                    'starts_at' => $slot['starts_at'],
                    'ends_at' => $slot['ends_at'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Utilisateur mis Ã  jour avec succÃ¨s',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->role === 'visitor' ? 'inactive' : 'active',
                'created_at' => $user->created_at?->toDateString(),
            ],
        ]);
    }

    public function destroy(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer votre propre compte',
            ], 403);
        }

        if ($request->user()?->role === 'directeur' && $user->role === 'admin') {
            return response()->json([
                'message' => "Un directeur ne peut pas supprimer un administrateur.",
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimÃ© avec succÃ¨s',
        ]);
    }
}

