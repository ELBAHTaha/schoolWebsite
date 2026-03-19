<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $classIds = $request->user()->class_id
            ? collect([$request->user()->class_id])
            : $request->user()->enrolledClasses()->pluck('classes.id');

        // Semaine courante ou demandée
        $week = (int)($request->query('week', date('W')));
        $year = (int)($request->query('year', date('Y')));
        // Premier jour de la semaine (lundi)
        $startOfWeek = new \DateTime();
        $startOfWeek->setISODate($year, $week);
        $endOfWeek = clone $startOfWeek;
        $endOfWeek->modify('+6 days');

        $dayMap = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche',
        ];

        $classes = SchoolClass::with(['professor.workingHours', 'room'])
            ->whereIn('id', $classIds)
            ->get();

        $schedules = collect();
        foreach ($classes as $class) {
            $hours = $class->professor?->workingHours ?? collect();
            foreach ($hours as $wh) {
                $schedules->push((object) [
                    'day_of_week' => $dayMap[$wh->day_of_week] ?? $wh->day_of_week,
                    'starts_at' => $wh->starts_at,
                    'ends_at' => $wh->ends_at,
                    'location' => $class->room?->name,
                    'schoolClass' => $class,
                ]);
            }
        }

        $schedules = $schedules->sortBy([
            ['day_of_week', 'asc'],
            ['starts_at', 'asc'],
        ])->values();

        return view('dashboard.student.schedule.index', [
            'schedules' => $schedules,
            'week' => $week,
            'year' => $year,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek,
        ]);
    }
}


