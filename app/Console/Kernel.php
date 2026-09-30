<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Notifications\DatabaseNotification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check SLA breaches every 5 minutes (the tightest SLA, urgent, is only 2 hours)
        $schedule->command('sla:check')->everyFiveMinutes();

        // Check for spam reports every 6 hours
        $schedule->command('reports:check-spam')->everySixHours();

        // Clean up old notifications (older than 30 days)
        $schedule->call(function () {
            DatabaseNotification::where('created_at', '<', now()->subDays(30))->delete();
        })->daily()->name('notifications:cleanup');

        // Clean up temporary files every 6 hours
        $schedule->command('files:cleanup')->everySixHours();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
