<?php

namespace App\Console\Commands;

use App\Events\SLABreached;
use App\Models\Complaint;
use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSLA extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sla:check';

    /**
     * The console command description.
     */
    protected $description = 'Check for SLA breaches and escalate reports/complaints';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking SLA breaches...');

        // Check reports with SLA breaches
        $breachedReports = Report::where('sla_due_at', '<', now())
            ->where('is_escalated', false)
            ->whereNotIn('status', ['closed', 'resolved', 'rejected'])
            ->get();

        foreach ($breachedReports as $report) {
            $this->escalateReport($report);
        }

        // Check complaints with SLA breaches
        $breachedComplaints = Complaint::where('sla_due_at', '<', now())
            ->where('is_escalated', false)
            ->whereNotIn('status', ['closed', 'resolved', 'rejected'])
            ->get();

        foreach ($breachedComplaints as $complaint) {
            $this->escalateComplaint($complaint);
        }

        $totalBreached = $breachedReports->count() + $breachedComplaints->count();

        if ($totalBreached > 0) {
            $this->info("Escalated {$totalBreached} items due to SLA breach.");
            Log::info("SLA Check: Escalated {$totalBreached} items", [
                'reports' => $breachedReports->count(),
                'complaints' => $breachedComplaints->count(),
            ]);
        } else {
            $this->info('No SLA breaches found.');
        }

        return self::SUCCESS;
    }

    private function escalateReport(Report $report)
    {
        $oldPriority = $report->priority;

        // saveQuietly skips the model's updating hook, which would treat the priority bump as a
        // re-triage and push sla_due_at forward, making the breach disappear.
        $report->fill([
            'is_escalated' => true,
            'priority' => 'urgent',
            'last_activity_at' => now(),
        ])->saveQuietly();

        \App\Models\AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'user_id' => null, // system action, not performed by any user
            'event' => 'sla_breached_escalated',
            'old_values' => ['priority' => $oldPriority, 'is_escalated' => false],
            'new_values' => ['priority' => 'urgent', 'is_escalated' => true],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Console/CheckSLA',
        ]);

        // Fire SLA breach event
        event(new SLABreached($report));

        $this->line("Escalated report: {$report->ticket_no}");
    }

    private function escalateComplaint(Complaint $complaint)
    {
        $oldPriority = $complaint->priority ?? 'medium';

        // See escalateReport(): keep the original sla_due_at so the breach stays visible.
        $complaint->fill([
            'is_escalated' => true,
            'priority' => 'urgent',
            'last_activity_at' => now(),
        ])->saveQuietly();

        \App\Models\AuditLog::create([
            'auditable_type' => Complaint::class,
            'auditable_id' => $complaint->id,
            'user_id' => null, // system action, not performed by any user
            'event' => 'sla_breached_escalated',
            'old_values' => ['priority' => $oldPriority, 'is_escalated' => false],
            'new_values' => ['priority' => 'urgent', 'is_escalated' => true],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Console/CheckSLA',
        ]);

        // Fire SLA breach event
        event(new SLABreached($complaint));

        $this->line("Escalated complaint: {$complaint->ticket_no}");
    }
}
