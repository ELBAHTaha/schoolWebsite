<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\CMIPlaceholderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class PaymentManagementController extends Controller
{
    public function __construct(private readonly CMIPlaceholderService $cmiPlaceholderService)
    {
    }

    public function index(): View
    {
        $payments = Payment::with('student')->latest()->paginate(25);
        $students = User::where('role', 'student')->orderBy('name')->get();

        return view('dashboard.secretary.payments.index', compact('payments', 'students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'between:2020,2100'],
            'status' => ['required', 'in:paid,unpaid,late'],
            'payment_method' => ['required', 'in:cmi,cash'],
        ]);

        Payment::updateOrCreate([
            'student_id' => $validated['student_id'],
            'month' => $validated['month'],
            'year' => $validated['year'],
        ], $validated);

        return back()->with('status', 'Payment saved.');
    }

    public function remindLate(Payment $payment): RedirectResponse
    {
        if ($payment->status !== 'late') {
            return back()->with('error', 'Reminder available only for late payments.');
        }

        return back()->with('status', 'Late reminder queued (basic logic placeholder).');
    }

    public function receipt(Payment $payment)
    {
        $content = "Receipt #{$payment->id}\nStudent: {$payment->student->name}\nAmount: {$payment->amount}\nStatus: {$payment->status}";

        return Response::make($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="receipt-'.$payment->id.'.pdf"',
        ]);
    }

    public function cmiPlaceholder(Payment $payment): RedirectResponse
    {
        $payload = $this->cmiPlaceholderService->buildPaymentPayload($payment);

        return back()->with('status', 'CMI placeholder prepared: '.$payload['reference']);
    }
}
