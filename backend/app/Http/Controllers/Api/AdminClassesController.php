<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminClassesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        $query = SchoolClass::with(['professor', 'room']);

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $classes = $query->latest()->paginate(20);

        return response()->json([
            'data' => $classes->map(fn (SchoolClass $class) => [
                'id' => $class->id,
                'name' => $class->name,
                'description' => $class->description,
                'professor_name' => $class->professor?->name,
                'room_name' => $class->room?->name,
                'price_1_month' => $class->price_1_month,
                'price_3_month' => $class->price_3_month,
                'price_6_month' => $class->price_6_month,
                'students_count' => $class->students()->count(),
                'status' => 'Actif', // Could be based on some logic
                'created_at' => $class->created_at?->toDateString(),
            ]),
            'total' => $classes->total(),
            'per_page' => $classes->perPage(),
            'current_page' => $classes->currentPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'professor_id' => ['nullable', 'exists:users,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'price_1_month' => ['nullable', 'numeric', 'min:0'],
            'price_3_month' => ['nullable', 'numeric', 'min:0'],
            'price_6_month' => ['nullable', 'numeric', 'min:0'],
        ]);

        $class = SchoolClass::create($validated);

        return response()->json([
            'message' => 'Cours créé avec succès',
            'class' => [
                'id' => $class->id,
                'name' => $class->name,
                'description' => $class->description,
                'professor_name' => $class->professor?->name,
                'room_name' => $class->room?->name,
                'price_1_month' => $class->price_1_month,
                'price_3_month' => $class->price_3_month,
                'price_6_month' => $class->price_6_month,
            ],
        ], 201);
    }

    public function update(Request $request, SchoolClass $class): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'professor_id' => ['nullable', 'exists:users,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'price_1_month' => ['nullable', 'numeric', 'min:0'],
            'price_3_month' => ['nullable', 'numeric', 'min:0'],
            'price_6_month' => ['nullable', 'numeric', 'min:0'],
        ]);

        $class->update($validated);

        return response()->json([
            'message' => 'Cours mis à jour avec succès',
            'class' => [
                'id' => $class->id,
                'name' => $class->name,
                'description' => $class->description,
                'professor_name' => $class->professor?->name,
                'room_name' => $class->room?->name,
                'price_1_month' => $class->price_1_month,
                'price_3_month' => $class->price_3_month,
                'price_6_month' => $class->price_6_month,
            ],
        ]);
    }

    public function destroy(SchoolClass $class): JsonResponse
    {
        $class->delete();

        return response()->json([
            'message' => 'Cours supprimé avec succès',
        ]);
    }
}
