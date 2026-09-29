<?php

namespace App\Listeners;

use App\Events\ReportAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendReportAssignedNotification implements ShouldQueue
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
    public function handle(ReportAssigned $event): void
    {
        $report = $event->report;
        $assignedTo = $event->assignedTo;

        if (! $assignedTo) {
            return;
        }

        // Send notification to assigned user if settings allow
        if ($assignedTo->getSettings('notifications.assignments', true) && $assignedTo->getSettings('notifications.status', true)) {
            $assignedTo->notify(new \App\Notifications\ReportAssignedNotification($report));
        }

        // Send email notification (with error handling and settings check)
        if ($assignedTo->getSettings('notifications.email', true)) {
            try {
                Mail::to($assignedTo->email)->send(new \App\Mail\ReportAssignedMail($report, $assignedTo));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to send assignment email: '.$e->getMessage());
            }
        }

        // Send notification to original user if settings allow and not the same user
        if ($report->user && (int) $report->user->id !== (int) $assignedTo->id) {
            if ($report->user->getSettings('notifications.status', true)) {
                $report->user->notify(new \App\Notifications\ReportAssignedToStaffNotification($report, $assignedTo));
            }
        }
    }
}
