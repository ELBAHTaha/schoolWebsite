<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Pdf;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseMaterialController extends Controller
{
    public function index(Request $request): View
    {
        $professor = $request->user();
        $classIds = SchoolClass::where('professor_id', $professor->id)->pluck('id');
        $materials = Pdf::whereIn('class_id', $classIds)->latest()->paginate(20);
        $classes = SchoolClass::whereIn('id', $classIds)->orderBy('name')->get();

        return view('dashboard.professor.materials.index', compact('materials', 'classes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_id' => ['required', 'exists:classes,id'],
            'title' => ['required', 'string', 'max:255'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $ownsClass = SchoolClass::where('id', $validated['class_id'])
            ->where('professor_id', $request->user()->id)
            ->exists();
        abort_unless($ownsClass, 403);

        $path = $request->file('pdf')->store('courses', 'public');

        Pdf::create([
            'class_id' => $validated['class_id'],
            'title' => $validated['title'],
            'file_path' => $path,
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Course PDF uploaded.');
    }
}
