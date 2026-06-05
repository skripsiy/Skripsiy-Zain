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
        Schema::table('tickets', function (Blueprint $table) {
            // Channel asal tiket (misal: 21, 2, 19, 4, 40, 54)
            $table->string('channel', 50)->nullable()->after('escalationStatus');
            // Sistem asal tiket: DSC atau INSERA
            $table->string('source_system', 20)->nullable()->after('channel');
            // Pool ID / Owner Group (misal: new_site179 BESFIXED, SALAM SIMPATIK)
            $table->string('pool_id', 150)->nullable()->after('source_system');
            // Tier urgensi: 1=Low Emergency, 2=Emergency, 3=Super Emergency, 4=HVC, 5=VVIP/Management
            $table->tinyInteger('urgency_level')->nullable()->default(1)->after('pool_id');
            // Divisi yang berhak menangani tiket ini (ditentukan otomatis saat masuk)
            $table->enum('division_target', ['area', 'besfixed', 'saltik'])->nullable()->after('urgency_level');
            // Waktu auto-assign dijalankan
            $table->timestamp('auto_assigned_at')->nullable()->after('division_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'channel', 'source_system', 'pool_id',
                'urgency_level', 'division_target', 'auto_assigned_at'
            ]);
        });
    }
};
