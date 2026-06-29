<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix P-4: Tambahkan database index pada kolom yang sering digunakan
     * sebagai filter/sort pada query tiket di Dashboard, Ticket List, dan Report.
     *
     * Tanpa index, setiap query melakukan full table scan.
     * Dengan index, MySQL dapat menemukan baris yang relevan jauh lebih cepat.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Index single-column untuk filter yang paling sering dipakai
            $table->index(['condition'],   'idx_tickets_condition');
            $table->index(['status'],      'idx_tickets_status');
            $table->index(['datereport'],  'idx_tickets_datereport');
            $table->index(['created_at'],  'idx_tickets_created_at');
            $table->index(['datesolved'],  'idx_tickets_datesolved');
            $table->index(['urgency_level'], 'idx_tickets_urgency_level');

            // Composite index untuk query yang sering dikombinasikan
            // Contoh: WHERE assigned_to_user_id = ? AND condition IN (...)
            $table->index(['assigned_to_user_id', 'condition'], 'idx_tickets_assigned_condition');

            // Contoh: WHERE solved_by_user_id = ? AND condition IN (...)
            $table->index(['solved_by_user_id', 'condition'],   'idx_tickets_solved_condition');

            // Index untuk filter laporan berdasarkan tanggal + agent
            $table->index(['assigned_to_user_id', 'datereport'], 'idx_tickets_assigned_date');
            $table->index(['solved_by_user_id', 'datesolved'],   'idx_tickets_solved_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex('idx_tickets_condition');
            $table->dropIndex('idx_tickets_status');
            $table->dropIndex('idx_tickets_datereport');
            $table->dropIndex('idx_tickets_created_at');
            $table->dropIndex('idx_tickets_datesolved');
            $table->dropIndex('idx_tickets_urgency_level');
            $table->dropIndex('idx_tickets_assigned_condition');
            $table->dropIndex('idx_tickets_solved_condition');
            $table->dropIndex('idx_tickets_assigned_date');
            $table->dropIndex('idx_tickets_solved_date');
        });
    }
};
