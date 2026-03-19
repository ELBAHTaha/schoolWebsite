<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecretaryProfessorsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('search', ''));

        $query = User::query()
            ->where('role', 'professor')
            ->orderBy('name');

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $professors = $query->get();

        return response()->json([
            'data' => $professors->map(fn (User $professor) => [
                'id' => $professor->id,
                'name' => $professor->name,
            ]),
        ]);
    }
}
