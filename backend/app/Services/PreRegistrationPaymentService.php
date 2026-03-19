<?php

namespace App\Services;

use App\Models\PreRegistrationLead;

class PreRegistrationPaymentService
{
    public function buildRedirectUrl(PreRegistrationLead $lead): string
    {
        $baseUrl = rtrim((string) config('services.cmi.base_url'), '/');
        $reference = 'PRE-REG-' . $lead->id . '-' . now()->timestamp;

        return $baseUrl . '/checkout?ref=' . $reference;
    }
}
