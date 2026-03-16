<?php

namespace App\Services;

use App\Models\PreRegistrationLead;
use App\Models\User;

class LeadAssignmentService
{
    public function createLead(array $payload): PreRegistrationLead
    {
        $commercials = User::where('role', 'commercial')->orderBy('id')->get();
        $assignedCommercialId = null;

        if ($commercials->isNotEmpty()) {
            $lastLead = PreRegistrationLead::whereNotNull('assigned_commercial_id')
                ->orderByDesc('id')
                ->first();

            if (! $lastLead) {
                $assignedCommercialId = $commercials->first()->id;
            } else {
                $currentIndex = $commercials->search(fn (User $user) => $user->id === $lastLead->assigned_commercial_id);
                if ($currentIndex === false) {
                    $assignedCommercialId = $commercials->first()->id;
                } else {
                    $nextIndex = ($currentIndex + 1) % $commercials->count();
                    $assignedCommercialId = $commercials[$nextIndex]->id;
                }
            }
        }

        return PreRegistrationLead::create([
            ...$payload,
            'status' => 'new',
            'assigned_commercial_id' => $assignedCommercialId,
        ]);
    }
}
