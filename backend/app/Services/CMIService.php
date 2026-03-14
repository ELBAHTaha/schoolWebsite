<?php

namespace App\Services;

use App\Models\Payment;

class CMIService
{
    public function createPaymentRequest(Payment $payment): array
    {
        return [
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => 'MAD',
            'redirect_url' => config('services.cmi.base_url').'/checkout?ref='.$payment->id,
            'callback_url' => route('payments.callback.cmi'),
        ];
    }

    public function handleCallback(array $payload): array
    {
        return [
            'payment_id' => (int) ($payload['order_id'] ?? 0),
            'transaction_id' => (string) ($payload['transaction_id'] ?? ''),
            'signature' => (string) ($payload['signature'] ?? ''),
            'raw' => $payload,
        ];
    }

    public function verifyTransaction(array $transactionData): bool
    {
        if (empty($transactionData['payment_id']) || empty($transactionData['transaction_id'])) {
            return false;
        }

        // Replace with real CMI signature verification and status API call.
        return true;
    }
}
