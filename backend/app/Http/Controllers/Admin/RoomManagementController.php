<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomManagementController extends Controller
{
    public function index(): View
    {
        $rooms = Room::latest()->paginate(20);

        return view('dashboard.admin.rooms.index', compact('rooms'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:rooms,name'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        Room::create($validated);

        return back()->with('status', 'Room created.');
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:rooms,name,'.$room->id],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $room->update($validated);

        return back()->with('status', 'Room updated.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        return back()->with('status', 'Room deleted.');
    }
}
