<?php

namespace App\Services;

use App\Models\Payment;

class CMIPlaceholderService
{
    public function buildPaymentPayload(Payment $payment): array
    {
        return [
            'reference' => 'CMI-PLACEHOLDER-'.$payment->id.'-'.now()->timestamp,
            'amount' => (float) $payment->amount,
            'currency' => 'MAD',
            'status' => 'mocked',
        ];
    }
}
