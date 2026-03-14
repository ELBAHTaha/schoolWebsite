<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Mail\PaymentConfirmationMail;
use App\Models\Payment;
use App\Services\CMIService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(private readonly CMIService $cmiService)
    {
    }

    public function create(StorePaymentRequest $request): RedirectResponse
    {
        Payment::create($request->validated());

        return back()->with('status', 'Paiement cree avec succes.');
    }

    public function requestCmiPayment(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless($request->user()->id === $payment->student_id, 403);

        $payload = $this->cmiService->createPaymentRequest($payment);

        return redirect()->away($payload['redirect_url']);
    }

    public function callback(Request $request): RedirectResponse
    {
        $result = $this->cmiService->handleCallback($request->all());

        $payment = Payment::findOrFail($result['payment_id']);

        if ($this->cmiService->verifyTransaction($result)) {
            $payment->update([
                'status' => 'paid',
                'payment_method' => 'cmi',
                'transaction_id' => $result['transaction_id'],
            ]);

            Mail::to($payment->student->email)->queue(new PaymentConfirmationMail($payment));

            return redirect()->route('student.dashboard')->with('status', 'Paiement confirme.');
        }

        $payment->update(['status' => 'late']);

        return redirect()->route('student.dashboard')->with('error', 'Echec de verification CMI.');
    }

    public function confirmCash(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status' => 'paid',
            'payment_method' => 'cash',
        ]);

        Mail::to($payment->student->email)->queue(new PaymentConfirmationMail($payment));

        return back()->with('status', 'Paiement en especes confirme.');
    }
}
