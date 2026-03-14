<?php

namespace App\Console;

use App\Console\Commands\SendPaymentReminders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SendPaymentReminders::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('payments:send-reminders')->monthlyOn(1, '08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
