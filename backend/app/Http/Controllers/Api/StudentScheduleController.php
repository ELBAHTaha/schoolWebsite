<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $student = $request->user();
        $classIds = $student?->enrolledClasses()->pluck('classes.id') ?? collect();

        if ($student?->class_id && ! $classIds->contains($student->class_id)) {
            $classIds->push($student->class_id);
        }

        $classIds = $classIds->filter()->values();

        $schedules = $classIds->isEmpty()
            ? collect()
            : Schedule::with('schoolClass.room')
                ->whereIn('class_id', $classIds)
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
        ]);
    }
}
