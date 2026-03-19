<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecretaryRoomsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        $query = Room::query()->orderBy('name');

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
}
