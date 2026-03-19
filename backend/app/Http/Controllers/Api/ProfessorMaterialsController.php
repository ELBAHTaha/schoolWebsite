<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfessorMaterialsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $materials = Material::with('schoolClass')
            ->where('professor_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $materials->map(fn (Material $material) => [
                'id' => $material->id,
                'title' => $material->title,
                'class_name' => $material->schoolClass?->name,
                'file_path' => $material->file_path,
                'file_url' => $material->file_path
                    ? Storage::disk('public')->url($material->file_path)
                    : null,
                'created_at' => $material->created_at?->toDateString(),
            ]),
            'total' => $materials->total(),
            'per_page' => $materials->perPage(),
            'current_page' => $materials->currentPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'document' => ['required', 'file', 'max:20480'],
        ]);

        $class = SchoolClass::where('id', $validated['class_id'])
            ->where('professor_id', $request->user()->id)
            ->first();
        $hasSchedule = Schedule::where('class_id', $validated['class_id'])
            ->where('professor_id', $request->user()->id)
            ->exists();
        if (! $class && ! $hasSchedule) {
            abort(403, 'Vous n\'avez pas accès à ce cours.');
        }

        $path = $request->file('document')->store('materials', 'public');

        $material = Material::create([
            'class_id' => $validated['class_id'],
            'professor_id' => $request->user()->id,
            'title' => $validated['title'],
            'file_path' => $path,
        ]);

        return response()->json([
            'message' => 'Document ajouté avec succès',
            'material' => [
                'id' => $material->id,
                'title' => $material->title,
                'class_name' => $material->schoolClass?->name,
                'file_path' => $material->file_path,
                'file_url' => $material->file_path
                    ? Storage::disk('public')->url($material->file_path)
                    : null,
            ],
        ], 201);
    }

    public function destroy(Request $request, Material $material): JsonResponse
    {
        if ($material->professor_id !== $request->user()->id) {
            abort(403, 'Vous n\'avez pas accès à ce document.');
        }

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return response()->json([
            'message' => 'Document supprimé avec succès',
        ]);
    }
}


