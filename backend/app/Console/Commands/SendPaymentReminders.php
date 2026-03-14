<?php

namespace App\Console\Commands;

use App\Mail\PaymentReminderMail;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPaymentReminders extends Command
{
    protected $signature = 'payments:send-reminders';

    protected $description = 'Send monthly reminders for unpaid student payments';

    public function handle(): int
    {
        $payments = Payment::with('student')
            ->whereIn('status', ['unpaid', 'late'])
            ->where('month', (int) now()->format('n'))
            ->where('year', (int) now()->format('Y'))
            ->get();

        foreach ($payments as $payment) {
            if ($payment->student?->email) {
                Mail::to($payment->student->email)->queue(new PaymentReminderMail($payment));
            }
        }

        $this->info('Reminders queued: '.$payments->count());

        return self::SUCCESS;
    }
}
