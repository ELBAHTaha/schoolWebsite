<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminRoomsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        $query = Room::query()->orderByDesc('created_at');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $rooms = $query->get();

        return response()->json([
            'data' => $rooms->map(fn (Room $room) => [
                'id' => $room->id,
                'name' => $room->name,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'status' => 'Disponible',
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('rooms', 'name')],
            'capacity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $room = Room::create($validated);

        return response()->json([
            'message' => 'Salle créée avec succès',
            'room' => [
                'id' => $room->id,
                'name' => $room->name,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'status' => 'Disponible',
            ],
        ], 201);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('rooms', 'name')->ignore($room->id)],
            'capacity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $room->update($validated);

        return response()->json([
            'message' => 'Salle mise à jour avec succès',
            'room' => [
                'id' => $room->id,
                'name' => $room->name,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'status' => 'Disponible',
            ],
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json([
            'message' => 'Salle supprimée avec succès',
        ]);
    }
}

