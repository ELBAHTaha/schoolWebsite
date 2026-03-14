<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Homework;
use App\Models\Payment;
use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();

        $classIds = $student->enrolledClasses()->pluck('classes.id');

        $stats = [
            'classes' => $classIds->count(),
            'homeworks' => Homework::whereIn('class_id', $classIds)->count(),
            'pdfs' => Pdf::whereIn('class_id', $classIds)->count(),
            'unpaidPayments' => Payment::where('student_id', $student->id)
                ->whereIn('status', ['unpaid', 'late'])
                ->count(),
            'announcements' => Announcement::active()->where(function ($query) {
                $query->whereNull('target_role')
                    ->orWhere('target_role', 'student');
            })->count(),
        ];

        return view('dashboard.student', compact('stats'));
    }
}
