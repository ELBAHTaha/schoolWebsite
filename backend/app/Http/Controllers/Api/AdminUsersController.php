<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->role === 'visitor' ? 'inactive' : 'active',
                'created_at' => $user->created_at?->toDateString(),
            ],
        ], 201);
    }

    public function update(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,secretary,professor,student,visitor'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

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

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->role === 'visitor' ? 'inactive' : 'active',
                'created_at' => $user->created_at?->toDateString(),
            ],
        ]);
    }

    public function destroy(User $user): \Illuminate\Http\JsonResponse
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer votre propre compte',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }
}
