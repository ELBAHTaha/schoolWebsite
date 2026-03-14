<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;

class PublicAnnouncementController extends Controller
{
    public function index(): JsonResponse
    {
        $announcements = Announcement::query()
            ->active()
            ->where('is_public', true)
            ->orderByDesc('created_at')
            ->get(['id', 'title', 'content', 'start_date', 'end_date']);

        return response()->json([
            'data' => $announcements,
        ]);
    }
}
