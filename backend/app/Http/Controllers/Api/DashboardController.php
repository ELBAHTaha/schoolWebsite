<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Homework;
use App\Models\Payment;
use App\Models\Pdf;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(): \Illuminate\Http\JsonResponse
    {
        $month = now()->month;
        $year = now()->year;

        $stats = [
            'students' => User::where('role', 'student')->count(),
            'classes' => SchoolClass::count(),
            'monthly_revenue' => (float) Payment::where('status', 'paid')
                ->where('month', $month)
                ->where('year', $year)
                ->sum('amount'),
            'success_rate' => null,
        ];

        $recentStudents = User::where('role', 'student')
            ->latest()
            ->with('schoolClass')
            ->take(5)
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'course' => $user->schoolClass?->name,
                'date' => optional($user->created_at)->toDateString(),
                'payment_status' => $user->payment_status ?? 'pending',
            ]);

        return response()->json([
            'stats' => $stats,
            'recent_students' => $recentStudents,
        ]);
    }

    public function student(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $classes = $user?->classes()->with('professor')->get() ?? collect();

        if ($user?->class_id) {
            $primaryClass = $user->schoolClass()->with('professor')->first();
            if ($primaryClass && ! $classes->contains('id', $primaryClass->id)) {
                $classes->push($primaryClass);
            }
        }

        $classIds = $classes->pluck('id')->filter()->values();

        $homeworks = $classIds->isEmpty()
            ? collect()
            : Homework::with('schoolClass')
                ->whereIn('class_id', $classIds)
                ->orderBy('deadline')
                ->take(6)
                ->get();

        $documentsCount = $classIds->isEmpty()
            ? 0
            : Pdf::whereIn('class_id', $classIds)->count();

        $stats = [
            'courses' => $classes->count(),
            'homework_pending' => $homeworks->where('deadline', '>=', now()->toDateString())->count(),
            'documents' => $documentsCount,
            'payment_status' => $user?->payment_status ?? 'pending',
        ];

        return response()->json([
            'stats' => $stats,
            'courses' => $classes->map(fn (SchoolClass $class) => [
                'id' => $class->id,
                'name' => $class->name,
                'professor' => $class->professor?->name,
                'schedule' => null,
            ]),
            'homework' => $homeworks->map(fn (Homework $homework) => [
                'id' => $homework->id,
                'title' => $homework->title,
                'course' => $homework->schoolClass?->name,
                'due' => optional($homework->deadline)->toDateString(),
                'status' => optional($homework->deadline)->isPast() ? 'En retard' : 'À faire',
            ]),
        ]);
    }

    public function professor(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        $classes = SchoolClass::where('professor_id', $user?->id)->get();
        $classIds = $classes->pluck('id')->filter()->values();

        $assignmentsCount = Assignment::where('professor_id', $user?->id)->count();
        $sessionsCount = Schedule::where('professor_id', $user?->id)->count();

        $upcoming = Schedule::with(['schoolClass.room'])
            ->where('professor_id', $user?->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(fn (Schedule $schedule) => [
                'id' => $schedule->id,
                'title' => $schedule->schoolClass?->name
                    ? "Cours — {$schedule->schoolClass->name}"
                    : 'Cours',
                'time' => trim("{$schedule->day_of_week} • {$schedule->starts_at}"),
                'room' => $schedule->location
                    ?: $schedule->schoolClass?->room?->name
                    ?: 'À confirmer',
            ]);

        return response()->json([
            'stats' => [
                'classes' => $classes->count(),
                'assignments' => $assignmentsCount,
                'sessions' => $sessionsCount,
                'notifications' => 0,
            ],
            'upcoming' => $upcoming,
        ]);
    }

    public function secretary(): \Illuminate\Http\JsonResponse
    {
        $activeStudents = User::where('role', 'student')->count();
        $paymentsToday = Payment::whereDate('created_at', now()->toDateString())->count();
        $pendingReceipts = Payment::where('status', 'paid')->whereNull('transaction_id')->count();
        $requests = User::where('role', 'visitor')->count();

        $tasks = collect([
            [
                'title' => "Valider {$paymentsToday} paiements",
                'status' => "Aujourd'hui",
            ],
            [
                'title' => "Envoyer {$pendingReceipts} reçus",
                'status' => "Cette semaine",
            ],
            [
                'title' => "Traiter {$requests} demandes",
                'status' => "En attente",
            ],
        ])->filter(fn (array $task) => ! str_contains($task['title'], '0 '))->values();

        return response()->json([
            'stats' => [
                'students' => $activeStudents,
                'payments_today' => $paymentsToday,
                'receipts' => $pendingReceipts,
                'requests' => $requests,
            ],
            'tasks' => $tasks,
        ]);
    }
}
