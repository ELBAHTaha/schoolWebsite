<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $classIds = SchoolClass::where('professor_id', $request->user()->id)->pluck('id');
        $assignments = Assignment::with('schoolClass')
            ->where('professor_id', $request->user()->id)
            ->whereIn('class_id', $classIds)
            ->latest()
            ->paginate(20);

        return view('dashboard.professor.assignments.index', compact('assignments'));
    }

    public function create(Request $request): View
    {
        $classes = SchoolClass::where('professor_id', $request->user()->id)->orderBy('name')->get();

        return view('dashboard.professor.assignments.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
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

        Assignment::create([
            'class_id' => $validated['class_id'],
            'professor_id' => $request->user()->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'deadline' => $validated['due_date'],
            'document_path' => $documentPath,
        ]);

        return redirect()->route('professor.assignments.index')->with('status', 'Devoir cree.');
    }

    public function show(Request $request, Assignment $assignment): View
    {
        $this->assertOwnedAssignment($request->user()->id, $assignment);
        $assignment->load('schoolClass');

        return view('dashboard.professor.assignments.show', compact('assignment'));
    }

    public function edit(Request $request, Assignment $assignment): View
    {
        $this->assertOwnedAssignment($request->user()->id, $assignment);
        $classes = SchoolClass::where('professor_id', $request->user()->id)->orderBy('name')->get();

        return view('dashboard.professor.assignments.edit', compact('assignment', 'classes'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->assertOwnedAssignment($request->user()->id, $assignment);

        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'document' => ['nullable', 'file', 'max:5120'],
        ]);

        $this->assertProfessorOwnsClass($request->user()->id, (int) $validated['class_id']);

        $documentPath = $assignment->document_path;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('assignments', 'public');
        }

        $assignment->update([
            'class_id' => $validated['class_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'deadline' => $validated['due_date'],
            'document_path' => $documentPath,
        ]);

        return redirect()->route('professor.assignments.show', $assignment)->with('status', 'Devoir mis a jour.');
    }

    public function destroy(Request $request, Assignment $assignment): RedirectResponse
    {
        $this->assertOwnedAssignment($request->user()->id, $assignment);
        $assignment->delete();

        return redirect()->route('professor.assignments.index')->with('status', 'Devoir supprime.');
    }

    private function assertProfessorOwnsClass(int $professorId, int $classId): void
    {
        $ownsClass = SchoolClass::where('id', $classId)
            ->where('professor_id', $professorId)
            ->exists();

        abort_unless($ownsClass, 403);
    }

    private function assertOwnedAssignment(int $professorId, Assignment $assignment): void
    {
        abort_unless($assignment->professor_id === $professorId, 404);
    }
}
