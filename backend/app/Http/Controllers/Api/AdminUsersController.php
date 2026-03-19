<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Mail\AccountCreatedMail;
use App\Models\ProfessorWorkingHour;
use App\Models\SchoolClass;
use App\Models\Schedule;
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

        $query = User::query()
            ->with(['classes', 'schoolClass'])
            ->orderByDesc('created_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        $users = $query->paginate($limit);

        return response()->json([
            'data' => $users->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'status' => $user->role === 'visitor' ? 'inactive' : 'active',
                'class_ids' => $user->role === 'professor'
                    ? SchoolClass::where('professor_id', $user->id)->pluck('id')->values()
                    : $user->classes->pluck('id')->values(),
                'payment_status' => $user->payment_status,
                'created_at' => optional($user->created_at)->toDateString(),
            ]),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
        ]);
    }

    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
        $workingHours = $validated['working_hours'] ?? [];
        $classIds = $validated['class_ids'] ?? [];
        $classSchedules = $validated['class_schedules'] ?? [];
        $paymentStatus = $validated['payment_status'] ?? null;
        unset($validated['working_hours']);
        unset($validated['class_ids']);
        unset($validated['class_schedules']);
        unset($validated['payment_status']);
        $plainPassword = $validated['password'];
        $classId = $validated['class_id'] ?? null;

        if (empty($classId) && !empty($classIds)) {
            $classId = $classIds[0];
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($plainPassword),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'class_id' => $validated['role'] === 'student' ? $classId : null,
            'payment_status' => $validated['role'] === 'student' ? ($paymentStatus ?? 'pending') : null,
        ]);
        if ($user->role === 'student' && !empty($classIds)) {
            $user->classes()->sync($classIds);
        }
        if ($user->role === 'professor') {
            if (!empty($classIds)) {
                SchoolClass::whereIn('id', $classIds)->update(['professor_id' => $user->id]);
            }
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

            if (!empty($classSchedules)) {
                Schedule::where('professor_id', $user->id)->delete();
                foreach ($classSchedules as $schedule) {
                    Schedule::create([
                        'class_id' => $schedule['class_id'],
                        'professor_id' => $user->id,
                        'day_of_week' => $schedule['day_of_week'],
                        'starts_at' => $schedule['starts_at'],
                        'ends_at' => $schedule['ends_at'],
                    ]);
                }
            }
        }

        $loginUrl = rtrim(config('app.frontend_url'), '/') . '/login';
        try {
            Mail::to($user->email)->send(new AccountCreatedMail($user, $plainPassword, $loginUrl));
        } catch (\Throwable $e) {
            logger()->warning('Failed to send account created email.', [
                'userId' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
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
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
            'class_schedules' => ['nullable', 'array'],
            'class_schedules.*.class_id' => ['required', 'integer', 'exists:classes,id'],
            'class_schedules.*.day_of_week' => ['required', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'class_schedules.*.starts_at' => ['required', 'date_format:H:i'],
            'class_schedules.*.ends_at' => ['required', 'date_format:H:i'],
            'payment_status' => ['required_if:role,student', 'in:paid,pending,late'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['nullable', 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'],
            'working_hours.*.starts_at' => ['nullable', 'date_format:H:i'],
            'working_hours.*.ends_at' => ['nullable', 'date_format:H:i'],
        ]);

        $workingHours = $validated['working_hours'] ?? null;
        $classIds = $validated['class_ids'] ?? null;
        $classSchedules = $validated['class_schedules'] ?? null;
        $paymentStatus = $validated['payment_status'] ?? null;
        unset($validated['working_hours']);
        unset($validated['class_ids']);
        unset($validated['class_schedules']);
        unset($validated['payment_status']);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        if ($validated['role'] === 'student') {
            $classId = $validated['class_id'] ?? null;
            if (empty($classId) && is_array($classIds) && !empty($classIds)) {
                $classId = $classIds[0];
            }
            $updateData['class_id'] = $classId;
            $updateData['payment_status'] = $paymentStatus ?? $user->payment_status ?? 'pending';
        } else {
            $updateData['class_id'] = null;
            $updateData['payment_status'] = null;
        }

        $user->update($updateData);

        if ($validated['role'] !== 'student') {
            $user->classes()->detach();
        } elseif (is_array($classIds)) {
            $user->classes()->sync($classIds);
        }

        if ($validated['role'] !== 'professor') {
            SchoolClass::where('professor_id', $user->id)->update(['professor_id' => null]);
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

        if ($validated['role'] === 'professor' && is_array($classIds)) {
            SchoolClass::whereIn('id', $classIds)->update(['professor_id' => $user->id]);
            SchoolClass::where('professor_id', $user->id)
                ->whereNotIn('id', $classIds)
                ->update(['professor_id' => null]);
        }

        if ($validated['role'] === 'professor' && is_array($classSchedules)) {
            Schedule::where('professor_id', $user->id)->delete();
            foreach ($classSchedules as $schedule) {
                Schedule::create([
                    'class_id' => $schedule['class_id'],
                    'professor_id' => $user->id,
                    'day_of_week' => $schedule['day_of_week'],
                    'starts_at' => $schedule['starts_at'],
                    'ends_at' => $schedule['ends_at'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
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
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }
}

