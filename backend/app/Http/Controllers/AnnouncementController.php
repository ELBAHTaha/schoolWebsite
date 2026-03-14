<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;

class AnnouncementController extends Controller
{
    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        Announcement::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Annonce publiee avec succes.');
    }
}
