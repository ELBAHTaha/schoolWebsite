<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $workingHours = $request->user()
            ->workingHours()
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();

        $schedules = Schedule::with('schoolClass.room')
            ->where('professor_id', $request->user()->id)
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->paginate(20);

        return view('dashboard.professor.schedules.index', compact('schedules', 'workingHours'));
    }
}
