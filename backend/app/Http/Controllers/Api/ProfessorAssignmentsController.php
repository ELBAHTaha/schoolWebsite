<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProfessorAssignmentsController extends Controller
{
    public function classes(Request $request): JsonResponse
    {
        $classes = SchoolClass::query()
            ->where('professor_id', $request->user()->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'data' => $classes,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $classIds = SchoolClass::where('professor_id', $request->user()->id)->pluck('id');
        $assignments = Assignment::with('schoolClass')
            ->where('professor_id', $request->user()->id)
            ->whereIn('class_id', $classIds)
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $assignments->map(fn (Assignment $assignment) => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'due_date' => $assignment->due_date?->toDateString(),
                'class_name' => $assignment->schoolClass?->name,
                'document_path' => $assignment->document_path,
                'created_at' => $assignment->created_at?->toDateString(),
            ]),
            'total' => $assignments->total(),
            'per_page' => $assignments->perPage(),
            'current_page' => $assignments->currentPage(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'document' => ['nullable', 'file', 'max:5120'],
        ]);

        $this->assertProfessorOwnsClass($request->user()->id, (int) $validated['class_id']);

        $documentPath = $request->file('document')?->store('assignments', 'public');

        $assignment = Assignment::create([
            'class_id' => $validated['class_id'],
            'professor_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'document_path' => $documentPath,
        ]);

        return response()->json([
            'message' => 'Devoir créé avec succès',
            'assignment' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'due_date' => $assignment->due_date?->toDateString(),
                'class_name' => $assignment->schoolClass?->name,
                'document_path' => $assignment->document_path,
            ],
        ], 201);
    }

    public function update(Request $request, Assignment $assignment): JsonResponse
    {
        $this->assertProfessorOwnsAssignment($request->user()->id, $assignment);

        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'document' => ['nullable', 'file', 'max:5120'],
        ]);

        $this->assertProfessorOwnsClass($request->user()->id, (int) $validated['class_id']);

        $documentPath = $request->file('document')?->store('assignments', 'public');

        $assignment->update([
            'class_id' => $validated['class_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'document_path' => $documentPath ?: $assignment->document_path,
        ]);

        return response()->json([
            'message' => 'Devoir mis à jour avec succès',
            'assignment' => [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'description' => $assignment->description,
                'due_date' => $assignment->due_date?->toDateString(),
                'class_name' => $assignment->schoolClass?->name,
                'document_path' => $assignment->document_path,
            ],
        ]);
    }

    public function destroy(Request $request, Assignment $assignment): JsonResponse
    {
        $this->assertProfessorOwnsAssignment($request->user()->id, $assignment);

        if ($assignment->document_path) {
            Storage::disk('public')->delete($assignment->document_path);
        }

        $assignment->delete();

        return response()->json([
            'message' => 'Devoir supprimé avec succès',
        ]);
    }

    private function assertProfessorOwnsClass(int $professorId, int $classId): void
    {
        $class = SchoolClass::where('id', $classId)->where('professor_id', $professorId)->first();
        if (!$class) {
            abort(403, 'Vous n\'avez pas accès à cette classe.');
        }
    }

    private function assertProfessorOwnsAssignment(int $professorId, Assignment $assignment): void
    {
        if ($assignment->professor_id !== $professorId) {
            abort(403, 'Vous n\'avez pas accès à ce devoir.');
        }
    }
}
