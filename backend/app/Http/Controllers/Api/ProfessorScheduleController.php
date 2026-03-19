<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProfessorWorkingHour;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfessorScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $schedules = Schedule::with('schoolClass.room')
            ->where('professor_id', $request->user()->id)
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();

        $workingHours = ProfessorWorkingHour::where('professor_id', $request->user()->id)
            ->orderBy('day_of_week')
            ->orderBy('starts_at')
            ->get();

        return response()->json([
            'data' => $schedules->map(fn (Schedule $schedule) => [
                'id' => $schedule->id,
                'day_of_week' => $schedule->day_of_week,
                'starts_at' => $schedule->starts_at,
                'ends_at' => $schedule->ends_at,
                'course' => $schedule->schoolClass?->name,
                'room' => $schedule->location ?: $schedule->schoolClass?->room?->name,
            ]),
            'working_hours' => $workingHours->map(fn (ProfessorWorkingHour $hour) => [
                'day_of_week' => $hour->day_of_week,
                'starts_at' => $hour->starts_at,
                'ends_at' => $hour->ends_at,
            ]),
        ]);
    }
}
