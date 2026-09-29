<?php

namespace App\Listeners;

use App\Events\ReportStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendStatusChangedNotification implements ShouldQueue
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
    public function handle(ReportStatusChanged $event): void
    {
        $report = $event->report;
        $newStatus = $event->newStatus;

        // Send notification to report owner if exists and enabled in settings
        if ($report->user && $report->user->getSettings('notifications.status', true)) {
            $report->user->notify(new \App\Notifications\ReportStatusChangedNotification($report, $newStatus));
        }

        // Send email notification based on status if enabled in settings
        if ($report->user && $report->user->email && $report->user->getSettings('notifications.email', true)) {
            try {
                switch ($newStatus) {
                    case 'verified':
                        Mail::to($report->user->email)->send(new \App\Mail\ReportVerifiedMail($report));
                        break;
                    case 'rejected':
                        Mail::to($report->user->email)->send(new \App\Mail\ReportRejectedMail($report));
                        break;
                    case 'resolved':
                        Mail::to($report->user->email)->send(new \App\Mail\ReportResolvedMail($report));
                        break;
                    case 'closed':
                        Mail::to($report->user->email)->send(new \App\Mail\ReportClosedMail($report));
                        break;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to send status change email: '.$e->getMessage());
            }
        }

        // Notify assigned staff if status affects them and enabled in settings (prevent duplicate if assigned staff is also report owner)
        if ($report->assigned_to && $report->assignedUser && in_array($newStatus, ['in_progress', 'awaiting_info', 'resolved'])) {
            if ((int) $report->assigned_to !== (int) $report->user_id) {
                if ($report->assignedUser->getSettings('notifications.status', true)) {
                    $report->assignedUser->notify(new \App\Notifications\ReportStatusChangedNotification($report, $newStatus));
                }
            }
        }
    }
}
