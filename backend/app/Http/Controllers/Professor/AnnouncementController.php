<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $announcements = Announcement::with('schoolClass')
            ->where('created_by', $request->user()->id)
            ->whereNotNull('class_id')
            ->latest()
            ->paginate(20);

        return view('dashboard.professor.announcements.index', compact('announcements'));
    }

    public function create(Request $request): View
    {
        $classes = SchoolClass::where('professor_id', $request->user()->id)->orderBy('name')->get();

        return view('dashboard.professor.announcements.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $this->assertProfessorOwnsClass($request->user()->id, (int) $validated['class_id']);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'class_id' => $validated['class_id'],
            'target_role' => 'student',
            'created_by' => $request->user()->id,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()->route('professor.announcements.index')->with('status', 'Annonce publiee.');
    }

    public function show(Request $request, Announcement $announcement): View
    {
        $this->assertOwnedAnnouncement($request->user()->id, $announcement);
        $announcement->load('schoolClass');

        return view('dashboard.professor.announcements.show', compact('announcement'));
    }

    public function edit(Request $request, Announcement $announcement): View
    {
        $this->assertOwnedAnnouncement($request->user()->id, $announcement);
        $classes = SchoolClass::where('professor_id', $request->user()->id)->orderBy('name')->get();

        return view('dashboard.professor.announcements.edit', compact('announcement', 'classes'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->assertOwnedAnnouncement($request->user()->id, $announcement);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $this->assertProfessorOwnsClass($request->user()->id, (int) $validated['class_id']);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'class_id' => $validated['class_id'],
            'target_role' => 'student',
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        return redirect()->route('professor.announcements.show', $announcement)->with('status', 'Annonce mise a jour.');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->assertOwnedAnnouncement($request->user()->id, $announcement);
        $announcement->delete();

        return redirect()->route('professor.announcements.index')->with('status', 'Annonce supprimee.');
    }

    private function assertProfessorOwnsClass(int $professorId, int $classId): void
    {
        $ownsClass = SchoolClass::where('id', $classId)
            ->where('professor_id', $professorId)
            ->exists();

        abort_unless($ownsClass, 403);
    }

    private function assertOwnedAnnouncement(int $professorId, Announcement $announcement): void
    {
        abort_unless(
            $announcement->created_by === $professorId && $announcement->class_id !== null,
            404
        );
    }
}
