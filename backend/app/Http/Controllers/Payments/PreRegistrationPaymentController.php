<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\PreRegistrationLead;
use App\Services\PreRegistrationPaymentService;
use Illuminate\Http\RedirectResponse;

class PreRegistrationPaymentController extends Controller
{
    public function redirect(PreRegistrationLead $lead, PreRegistrationPaymentService $service): RedirectResponse
    {
        if ($lead->payment_method !== 'online') {
            $lead->update([
                'payment_method' => 'online',
                'payment_status' => 'pending',
            ]);
        }

        return redirect()->away($service->buildRedirectUrl($lead));
    }
}
