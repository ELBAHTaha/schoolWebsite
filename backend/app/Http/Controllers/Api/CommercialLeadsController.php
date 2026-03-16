<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreRegistrationLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommercialLeadsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $status = trim((string) $request->query('status', ''));
        $dateFrom = trim((string) $request->query('date_from', ''));
        $dateTo = trim((string) $request->query('date_to', ''));

        $query = PreRegistrationLead::query()
            ->where('assigned_commercial_id', $user?->id)
            ->orderByDesc('created_at');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $leads = $query->get();

        return response()->json([
            'data' => $leads->map(fn (PreRegistrationLead $lead) => [
                'id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'desired_program' => $lead->desired_program,
                'message' => $lead->message,
                'status' => $lead->status,
                'created_at' => optional($lead->created_at)->toDateTimeString(),
            ]),
            'total' => $leads->count(),
        ]);
    }

    public function update(Request $request, PreRegistrationLead $lead): JsonResponse
    {
        $user = $request->user();

        if ($lead->assigned_commercial_id !== $user?->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,confirmed,not_interested'],
        ]);

        $lead->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Lead status updated.',
            'lead' => [
                'id' => $lead->id,
                'status' => $lead->status,
            ],
        ]);
    }
}
