<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $classIds = $request->user()->class_id
            ? collect([$request->user()->class_id])
            : $request->user()->enrolledClasses()->pluck('classes.id');
        $assignments = Assignment::whereIn('class_id', $classIds)
            ->latest()
            ->get();

        return view('dashboard.student.assignments.index', compact('assignments'));
    }
}
