<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * All valid statuses across reports and complaints workflow.
     */
    protected array $statuses = [
        'submitted',
        'pending',
        'verified',
        'rejected',
        'assigned',
        'in_progress',
        'awaiting_info',
        'resolved',
        'pending_approval',
        'closed',
        'escalated',
        'confirmed',
        'reviewed',
        'awaiting_admin_approval',
        'awaiting_admin',
        'needs_revision',
        'investigating',
        'dismissed',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add completion_notes and final_notes to reports
        Schema::table('reports', function (Blueprint $table) {
            if (! Schema::hasColumn('reports', 'completion_notes')) {
                $table->text('completion_notes')->nullable()->after('resolution_notes');
            }
            if (! Schema::hasColumn('reports', 'final_notes')) {
                $table->text('final_notes')->nullable()->after('completion_notes');
            }
        });

        // Update status enum values in MySQL
        if (DB::getDriverName() === 'mysql') {
            $enumList = "'".implode("', '", $this->statuses)."'";
            DB::statement("ALTER TABLE `reports` MODIFY COLUMN `status` ENUM({$enumList}) NOT NULL DEFAULT 'submitted'");
            DB::statement("ALTER TABLE `complaints` MODIFY COLUMN `status` ENUM({$enumList}) NOT NULL DEFAULT 'submitted'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'final_notes')) {
                $table->dropColumn('final_notes');
            }
            if (Schema::hasColumn('reports', 'completion_notes')) {
                $table->dropColumn('completion_notes');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            $prevReports = "'pending', 'submitted', 'verified', 'rejected', 'assigned', 'in_progress', 'awaiting_info', 'resolved', 'pending_approval', 'closed', 'escalated'";
            $prevComplaints = "'submitted', 'verified', 'rejected', 'assigned', 'in_progress', 'awaiting_info', 'resolved', 'pending_approval', 'closed', 'escalated'";
            DB::statement("ALTER TABLE `reports` MODIFY COLUMN `status` ENUM({$prevReports}) NOT NULL DEFAULT 'submitted'");
            DB::statement("ALTER TABLE `complaints` MODIFY COLUMN `status` ENUM({$prevComplaints}) NOT NULL DEFAULT 'submitted'");
        }
    }
};
