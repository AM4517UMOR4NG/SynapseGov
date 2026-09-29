<?php

namespace App\Listeners;

use App\Events\SLABreached;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSLABreachNotification implements ShouldQueue
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
    public function handle(SLABreached $event): void
    {
        $report = $event->report;

        // Notify admin users
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            if ($admin->getSettings('notifications.status', true)) {
                $admin->notify(new \App\Notifications\SLABreachNotification($report));
            }
        }

        // Notify department head
        if ($report->department_id) {
            $departmentHead = User::where('role', 'department_head')
                ->where('department_id', $report->department_id)
                ->first();

            if ($departmentHead && $departmentHead->getSettings('notifications.status', true)) {
                $departmentHead->notify(new \App\Notifications\SLABreachNotification($report));
            }
        }

        // Notify assigned staff
        if ($report->assigned_to && $report->assignedUser) {
            if ($report->assignedUser->getSettings('notifications.status', true)) {
                $report->assignedUser->notify(new \App\Notifications\SLABreachNotification($report));
            }
        }

        // Send email notifications with try-catch and settings check
        foreach ($adminUsers as $admin) {
            if ($admin->getSettings('notifications.email', true)) {
                try {
                    Mail::to($admin->email)->send(new \App\Mail\SLABreachMail($report));
                } catch (\Throwable $e) {
                    Log::warning("Failed to send SLA breach email to admin {$admin->id}: ".$e->getMessage());
                }
            }
        }
    }
}
