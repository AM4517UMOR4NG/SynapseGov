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
            if (! Schema::hasColumn('reports', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('final_notes');
            }
        });

        Schema::table('complaints', function (Blueprint $table) {
            if (! Schema::hasColumn('complaints', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            if (Schema::hasColumn('complaints', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });

        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};
