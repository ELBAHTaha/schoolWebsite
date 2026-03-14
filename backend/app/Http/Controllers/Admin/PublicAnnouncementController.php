<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicAnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $announcements = Announcement::where('created_by', $request->user()->id)
            ->where('is_public', true)
            ->latest()
            ->paginate(20);

        return view('dashboard.shared.public_announcements.index', [
            'announcements' => $announcements,
            'routePrefix' => 'admin',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        Announcement::create([
            ...$validated,
            'created_by' => $request->user()->id,
            'target_role' => 'visitor',
            'class_id' => null,
            'is_public' => true,
        ]);

        return back()->with('status', 'Annonce publique créée.');
    }

    public function edit(Request $request, Announcement $announcement): View
    {
        $this->assertOwned($request->user()->id, $announcement);

        return view('dashboard.shared.public_announcements.edit', [
            'announcement' => $announcement,
            'routePrefix' => 'admin',
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->assertOwned($request->user()->id, $announcement);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $announcement->update([
            ...$validated,
            'is_public' => true,
            'target_role' => 'visitor',
            'class_id' => null,
        ]);

        return redirect()->route('admin.announcements.index')->with('status', 'Annonce publique mise à jour.');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->assertOwned($request->user()->id, $announcement);
        $announcement->delete();

        return back()->with('status', 'Annonce publique supprimée.');
    }

    private function assertOwned(int $userId, Announcement $announcement): void
    {
        abort_unless($announcement->created_by === $userId && $announcement->is_public, 404);
    }
}
