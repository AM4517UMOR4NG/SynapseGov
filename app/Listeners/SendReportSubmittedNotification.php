<?php

namespace App\Listeners;

use App\Events\ReportSubmitted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendReportSubmittedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReportSubmitted $event): void
    {
        $report = $event->report;

        // Send notification to admin users if enabled in settings
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            if ($admin->getSettings('notifications.reports', true)) {
                $admin->notify(new \App\Notifications\ReportSubmittedNotification($report));
            }
        }

        // Send notification to department head if assigned and enabled in settings
        if ($report->department_id) {
            $departmentHead = User::where('role', 'department_head')
                ->where('department_id', $report->department_id)
                ->first();

            if ($departmentHead && $departmentHead->getSettings('notifications.reports', true)) {
                $departmentHead->notify(new \App\Notifications\ReportSubmittedNotification($report));
            }
        }

        // Send email to user if enabled in settings
        if (! app()->environment('local', 'development') && $report->user && $report->user->email && $report->user->getSettings('notifications.email', true)) {
            try {
                Mail::to($report->user->email)->send(new \App\Mail\ReportSubmittedMail($report));
            } catch (\Exception $e) {
                \Log::warning('Failed to send email notification: '.$e->getMessage());
            }
        }
    }
}
