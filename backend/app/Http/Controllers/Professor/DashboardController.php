<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\SchoolClass;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $professor = $request->user();

        $stats = [
            'classes' => SchoolClass::where('professor_id', $professor->id)->count(),
            'assignments' => Assignment::where('professor_id', $professor->id)->count(),
            'pdf_courses' => Material::where('professor_id', $professor->id)->count(),
            'schedules' => Schedule::where('professor_id', $professor->id)->count(),
        ];

        $classes = SchoolClass::with('room')->where('professor_id', $professor->id)->orderBy('name')->get();
        $schedules = Schedule::with('schoolClass')->where('professor_id', $professor->id)
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();
        $workingHours = $professor->workingHours()
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();

        return view('dashboard.professor.index', compact('stats', 'professor', 'classes', 'schedules', 'workingHours'));
    }
}
