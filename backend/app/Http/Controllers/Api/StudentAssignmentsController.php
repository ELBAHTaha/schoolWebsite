<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentAssignmentsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();
        $classes = $student?->enrolledClasses()->pluck('classes.id') ?? collect();
        if ($student?->class_id && ! $classes->contains($student->class_id)) {
            $classes->push($student->class_id);
        }
        $classIds = $classes->filter()->values();

        $assignments = $classIds->isEmpty()
            ? collect()
            : Assignment::with('schoolClass')
                ->whereIn('class_id', $classIds)
                ->latest()
                ->get();

        return response()->json([
            'data' => $assignments->map(fn (Assignment $assignment) => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'course' => $assignment->schoolClass?->name,
                'due' => $assignment->due_date?->toDateString(),
                'status' => $assignment->due_date?->isPast() ? 'En retard' : 'À faire',
                'download_url' => $assignment->document_path
                    ? url("/api/student/assignments/{$assignment->id}/download")
                    : null,
            ]),
        ]);
    }

    public function download(Request $request, Assignment $assignment)
    {
        $student = $request->user();
        $classes = $student?->enrolledClasses()->pluck('classes.id') ?? collect();
        if ($student?->class_id && ! $classes->contains($student->class_id)) {
            $classes->push($student->class_id);
        }
        $classIds = $classes->filter()->values();

        if ($classIds->isEmpty() || ! $classIds->contains($assignment->class_id)) {
            abort(403);
        }

        if (! $assignment->document_path || ! Storage::disk('public')->exists($assignment->document_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($assignment->document_path);
    }
}
