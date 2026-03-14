<?php

namespace App\Policies;

use App\Models\Pdf;
use App\Models\User;

class PdfPolicy
{
    public function download(User $user, Pdf $pdf): bool
    {
        if (! $user->hasRole('student')) {
            return false;
        }

        return $user->enrolledClasses()->where('classes.id', $pdf->class_id)->exists();
    }
}
