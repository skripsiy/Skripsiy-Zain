<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pindahkan user dari divisi 'area' ke 'besfixed'
        DB::table('users')
            ->where('campaign', 'area')
            ->update([
                'campaign' => 'besfixed',
                'area' => 'BESFIXED'
            ]);

        // Pindahkan target divisi tiket dari 'area' ke 'besfixed'
        DB::table('tickets')
            ->where('division_target', 'area')
            ->update([
                'division_target' => 'besfixed'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pemindahan data ke 'besfixed' tidak reversibel secara otomatis karena
        // kita tidak dapat membedakan mana user/tiket yang aslinya 'area' vs 'besfixed'.
    }
};
