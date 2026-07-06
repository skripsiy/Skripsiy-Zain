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
        // Tabel users — sering di-query by role dan status
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role'], 'idx_users_role');
            $table->index(['status'], 'idx_users_status');
            $table->index(['role', 'status'], 'idx_users_role_status');
        });

        // Tabel sessions — dicari setiap request untuk auth session validation
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->index(['last_activity'], 'idx_sessions_last_activity');
            });
        }

        // Tabel activity_log (Spatie) — dibaca saat melihat detail tiket
        if (Schema::hasTable('activity_log')) {
            Schema::table('activity_log', function (Blueprint $table) {
                $table->index(['subject_type', 'subject_id'], 'idx_activity_subject');
                $table->index(['causer_id', 'created_at'], 'idx_activity_causer_date');
            });
        }

        // Tabel agent_work_sessions — dicari oleh WorkSessionController
        if (Schema::hasTable('agent_work_sessions')) {
            Schema::table('agent_work_sessions', function (Blueprint $table) {
                $table->index(['user_id', 'work_date'], 'idx_ws_user_date');
                $table->index(['status'], 'idx_ws_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_sessions_activity_log_and_work_sessions_tables', function (Blueprint $table) {
            //
        });
    }
};
