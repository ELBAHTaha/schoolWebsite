<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassManagementController extends Controller
{
    public function index(): View
    {
        $classes = SchoolClass::with(['professor', 'room'])->latest()->paginate(20);
        $professors = User::where('role', 'professor')->orderBy('name')->get();
        $rooms = Room::orderBy('name')->get();

        return view('dashboard.admin.classes.index', compact('classes', 'professors', 'rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'professor_id' => ['nullable', 'exists:users,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'price_1_month' => ['nullable', 'numeric', 'min:0'],
            'price_3_month' => ['nullable', 'numeric', 'min:0'],
            'price_6_month' => ['nullable', 'numeric', 'min:0'],
        ]);

        SchoolClass::create($validated);

        return back()->with('status', 'Class created.');
    }

    public function update(Request $request, SchoolClass $class): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'professor_id' => ['nullable', 'exists:users,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'price_1_month' => ['nullable', 'numeric', 'min:0'],
            'price_3_month' => ['nullable', 'numeric', 'min:0'],
            'price_6_month' => ['nullable', 'numeric', 'min:0'],
        ]);

        $class->update($validated);

        return back()->with('status', 'Class updated.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return back()->with('status', 'Class deleted.');
    }
}
