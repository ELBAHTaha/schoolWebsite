<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Payment;
use App\Models\Room;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $stats = [
            'users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'professors' => User::where('role', 'professor')->count(),
            'classes' => SchoolClass::count(),
            'rooms' => Room::count(),
            'unpaidPayments' => Payment::where('status', 'unpaid')->count(),
            'announcements' => Announcement::count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }
}
