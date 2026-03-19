<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentMaterialsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();
        $classes = $student?->classes()->get(['classes.id']) ?? collect();

        if ($student?->class_id && ! $classes->contains('id', $student->class_id)) {
            $classes->push(SchoolClass::select('id')->find($student->class_id));
        }

        $classIds = $classes->pluck('id')->filter()->values();

        $materials = $classIds->isEmpty()
            ? collect()
            : Material::with('schoolClass')
                ->whereIn('class_id', $classIds)
                ->latest()
                ->get();

        return response()->json([
            'data' => $materials->map(fn (Material $material) => [
                'id' => $material->id,
                'title' => $material->title,
                'course' => $material->schoolClass?->name,
                'file_path' => $material->file_path,
                'download_url' => $material->file_path
                    ? url("/api/student/materials/{$material->id}/download")
                    : null,
                'created_at' => $material->created_at?->toDateString(),
            ]),
        ]);
    }

    public function download(Request $request, Material $material)
    {
        $student = $request->user();
        $classes = $student?->classes()->get(['classes.id']) ?? collect();

        if ($student?->class_id && ! $classes->contains('id', $student->class_id)) {
            $classes->push(SchoolClass::select('id')->find($student->class_id));
        }

        $classIds = $classes->pluck('id')->filter()->values();
        if ($classIds->isEmpty() || ! $classIds->contains($material->class_id)) {
            abort(403);
        }

        if (! $material->file_path || ! Storage::disk('public')->exists($material->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($material->file_path);
    }
}
