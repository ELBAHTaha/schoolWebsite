<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $classIds = $this->professorClassIds($request->user()->id);
        $materials = Material::with('schoolClass')
            ->where('professor_id', $request->user()->id)
            ->whereIn('class_id', $classIds)
            ->latest()
            ->paginate(20);

        return view('dashboard.professor.materials.index', compact('materials'));
    }

    public function create(Request $request): View
    {
        $classes = SchoolClass::where('professor_id', $request->user()->id)->orderBy('name')->get();

        return view('dashboard.professor.materials.create', compact('classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $this->assertProfessorOwnsClass($request->user()->id, (int) $validated['class_id']);
        $path = $request->file('pdf')->store('materials', 'public');

        Material::create([
            'class_id' => $validated['class_id'],
            'professor_id' => $request->user()->id,
            'title' => $validated['title'],
            'file_path' => $path,
        ]);

        return redirect()->route('professor.materials.index')->with('status', 'Support PDF televerse.');
    }

    public function show(Request $request, Material $material): View
    {
        $this->assertOwnedMaterial($request->user()->id, $material);

        return view('dashboard.professor.materials.show', compact('material'));
    }

    public function edit(Request $request, Material $material): View
    {
        $this->assertOwnedMaterial($request->user()->id, $material);
        $classes = SchoolClass::where('professor_id', $request->user()->id)->orderBy('name')->get();

        return view('dashboard.professor.materials.edit', compact('material', 'classes'));
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $this->assertOwnedMaterial($request->user()->id, $material);

        $validated = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        $this->assertProfessorOwnsClass($request->user()->id, (int) $validated['class_id']);
        $material->fill([
            'class_id' => $validated['class_id'],
            'title' => $validated['title'],
        ]);

        if ($request->hasFile('pdf')) {
            Storage::disk('public')->delete($material->file_path);
            $material->file_path = $request->file('pdf')->store('materials', 'public');
        }

        $material->save();

        return redirect()->route('professor.materials.show', $material)->with('status', 'Support mis a jour.');
    }

    public function destroy(Request $request, Material $material): RedirectResponse
    {
        $this->assertOwnedMaterial($request->user()->id, $material);
        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return redirect()->route('professor.materials.index')->with('status', 'Support supprime.');
    }

    private function professorClassIds(int $professorId)
    {
        return SchoolClass::where('professor_id', $professorId)->pluck('id');
    }

    private function assertProfessorOwnsClass(int $professorId, int $classId): void
    {
        $ownsClass = SchoolClass::where('id', $classId)->where('professor_id', $professorId)->exists();
        abort_unless($ownsClass, 403);
    }

    private function assertOwnedMaterial(int $professorId, Material $material): void
    {
        abort_unless($material->professor_id === $professorId, 404);
    }
}
