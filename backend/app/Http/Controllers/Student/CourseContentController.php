<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseContentController extends Controller
{
    public function index(Request $request): View
    {
        $classIds = $request->user()->class_id
            ? collect([$request->user()->class_id])
            : $request->user()->enrolledClasses()->pluck('classes.id');

        $materials = Material::with('schoolClass')
            ->whereIn('class_id', $classIds)
            ->latest()
            ->get();

        $pdfs = Pdf::with('schoolClass')
            ->whereIn('class_id', $classIds)
            ->latest()
            ->get();

        return view('dashboard.student.course_content.index', compact('materials', 'pdfs'));
    }
}
