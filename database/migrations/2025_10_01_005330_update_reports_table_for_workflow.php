<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Add workflow fields
            $table->string('ticket_no')->unique()->after('id');
            // The status enum is defined in create_reports_table; no ->change() here, because
            // changing columns on Laravel 10 requires doctrine/dbal, which this project does not ship.
            $table->timestamp('sla_due_at')->nullable()->after('resolved_at');
            $table->boolean('is_escalated')->default(false)->after('sla_due_at');
            $table->integer('reassign_count')->default(0)->after('is_escalated');
            $table->timestamp('last_activity_at')->nullable()->after('reassign_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['ticket_no', 'sla_due_at', 'is_escalated', 'reassign_count', 'last_activity_at']);
        });
    }
};
