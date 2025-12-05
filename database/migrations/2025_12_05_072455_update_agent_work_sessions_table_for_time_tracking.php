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
        Schema::table('agent_work_sessions', function (Blueprint $table) {
            // Rename existing columns
            $table->renameColumn('start_time', 'shift_start');
            $table->renameColumn('end_time', 'shift_end');
        });
        
        Schema::table('agent_work_sessions', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['end_type', 'duration_minutes']);
            
            // Add new columns for time tracking
            $table->integer('total_online_seconds')->default(0)->after('shift_end');
            $table->integer('total_aux_seconds')->default(0)->after('total_online_seconds');
            $table->integer('aux_remaining_seconds')->default(3600)->after('total_aux_seconds'); // 1 hour
            $table->enum('status', ['offline', 'online', 'aux'])->default('offline')->after('aux_remaining_seconds');
            $table->timestamp('current_session_start')->nullable()->after('status');
            $table->date('work_date')->after('current_session_start');
            
            // Add index
            $table->index(['user_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_work_sessions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'work_date']);
            $table->dropColumn([
                'total_online_seconds',
                'total_aux_seconds',
                'aux_remaining_seconds',
                'status',
                'current_session_start',
                'work_date'
            ]);
            $table->string('end_type')->nullable();
            $table->integer('duration_minutes')->nullable();
        });
        
        Schema::table('agent_work_sessions', function (Blueprint $table) {
            $table->renameColumn('shift_start', 'start_time');
            $table->renameColumn('shift_end', 'end_time');
        });
    }
};
