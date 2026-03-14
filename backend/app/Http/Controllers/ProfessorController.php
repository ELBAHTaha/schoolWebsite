<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Homework;
use App\Models\Pdf;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessorController extends Controller
{
    public function index(Request $request): View
    {
        $professorId = $request->user()->id;

        $stats = [
            'classes' => SchoolClass::where('professor_id', $professorId)->count(),
            'homeworks' => Homework::where('created_by', $professorId)->count(),
            'pdfs' => Pdf::where('uploaded_by', $professorId)->count(),
            'announcements' => Announcement::where('created_by', $professorId)->count(),
        ];

        return view('dashboard.professor', compact('stats'));
    }
}
