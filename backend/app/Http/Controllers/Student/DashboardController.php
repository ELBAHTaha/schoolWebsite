<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Material;
use App\Models\Payment;
use App\Models\Pdf;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();
        $classIds = $student->class_id ? collect([$student->class_id]) : $student->enrolledClasses()->pluck('classes.id');

        $stats = [
            'assignments' => Assignment::whereIn('class_id', $classIds)->count(),
            'pdfs' => Material::whereIn('class_id', $classIds)->count() + Pdf::whereIn('class_id', $classIds)->count(),
            'schedules' => Schedule::whereIn('class_id', $classIds)->count(),
            'unpaid_payments' => Payment::where('student_id', $student->id)->whereIn('status', ['unpaid', 'late'])->count(),
            'announcements' => Announcement::active()->where(function ($query) use ($classIds) {
                $query->whereNull('target_role')
                    ->orWhere('target_role', 'student')
                    ->orWhereIn('class_id', $classIds);
            })->count(),
        ];

        $assignments = Assignment::whereIn('class_id', $classIds)->latest()->limit(10)->get();
        $pdfMaterials = Material::whereIn('class_id', $classIds)->latest()->limit(10)->get();
        $pdfDocs = Pdf::whereIn('class_id', $classIds)->latest()->limit(10)->get();
        $pdfs = $pdfMaterials->concat($pdfDocs)->sortByDesc('created_at')->take(10)->values();
        $schedules = collect();
        $classes = SchoolClass::with(['professor.workingHours', 'room'])
            ->whereIn('id', $classIds)
            ->get();
        $stats['schedules'] = $classes->sum(function ($class) {
            return $class->professor?->workingHours?->count() ?? 0;
        });
        $dayMap = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];
        foreach ($classes as $class) {
            $hours = $class->professor?->workingHours ?? collect();
            foreach ($hours as $wh) {
                $schedules->push((object) [
                    'day_of_week' => $dayMap[$wh->day_of_week] ?? $wh->day_of_week,
                    'starts_at' => $wh->starts_at,
                    'ends_at' => $wh->ends_at,
                    'schoolClass' => $class,
                ]);
            }
        }
        $schedules = $schedules->sortBy([
            ['day_of_week', 'asc'],
            ['starts_at', 'asc'],
        ])->values();
        $payments = Payment::where('student_id', $student->id)->latest()->get();
        $announcements = Announcement::active()->where(function ($query) use ($classIds) {
            $query->whereNull('target_role')
                ->orWhere('target_role', 'student')
                ->orWhereIn('class_id', $classIds);
        })->latest()->limit(10)->get();

        return view('dashboard.student.index', compact('stats', 'assignments', 'pdfs', 'schedules', 'payments', 'announcements', 'classes'));
    }
}



