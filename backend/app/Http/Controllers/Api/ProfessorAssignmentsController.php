<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProfessorAssignmentsController extends Controller
{
    public function classes(Request $request): JsonResponse
    {
        $professorId = $request->user()->id;
        $classIds = SchoolClass::where('professor_id', $professorId)->pluck('id');
        $scheduleClassIds = Schedule::where('professor_id', $professorId)->pluck('class_id');
        $classIds = $classIds->merge($scheduleClassIds)->filter()->unique()->values();

        logger()->info('professor.classes debug', [
            'professor_id' => $professorId,
            'class_ids_by_professor' => $classIds->values(),
            'schedule_class_ids' => $scheduleClassIds->values(),
        ]);

        $classes = SchoolClass::query()
            ->with([
                'room',
                'schedules' => fn ($q) => $q->orderBy('day_of_week')->orderBy('starts_at'),
            ])
            ->whereIn('id', $classIds)
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $classes->map(function (SchoolClass $class) {
                $schedule = $class->schedules->first();
                $studentIds = $class->students()->pluck('users.id')
                    ->merge($class->primaryStudents()->pluck('users.id'))
                    ->unique();

                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'room_name' => $class->room?->name,
                    'students_count' => $studentIds->count(),
                    'next_schedule' => $schedule
                        ? [
                            'day_of_week' => $schedule->day_of_week,
                            'starts_at' => $schedule->starts_at,
                            'ends_at' => $schedule->ends_at,
                          ]
                        : null,
                ];
            }),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $professorId = $request->user()->id;
        $classIds = SchoolClass::where('professor_id', $professorId)->pluck('id');
        $scheduleClassIds = Schedule::where('professor_id', $professorId)->pluck('class_id');
        $classIds = $classIds->merge($scheduleClassIds)->filter()->unique()->values();
        $assignments = Assignment::with('schoolClass')
            ->where('professor_id', $professorId)
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
                'document_url' => $assignment->document_path
                    ? Storage::disk('public')->url($assignment->document_path)
                    : null,
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
                'document_url' => $assignment->document_path
                    ? Storage::disk('public')->url($assignment->document_path)
                    : null,
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
                'document_url' => $assignment->document_path
                    ? Storage::disk('public')->url($assignment->document_path)
                    : null,
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
        $hasSchedule = Schedule::where('class_id', $classId)->where('professor_id', $professorId)->exists();
        if (! $class && ! $hasSchedule) {
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

