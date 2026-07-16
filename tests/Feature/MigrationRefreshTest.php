<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationRefreshTest extends TestCase
{
    /**
     * Test: Memastikan migrate:refresh berjalan lancar tanpa error di SQLite.
     */
    public function test_migration_refresh_runs_successfully()
    {
        // Jalankan artisan migrate:refresh
        $this->artisan('migrate:refresh')
            ->assertExitCode(0);

        // Verifikasi tabel-tabel utama masih ada setelah refresh
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('tickets'));
        $this->assertTrue(Schema::hasTable('agent_work_sessions'));

        // Verifikasi bahwa indeks duplikat tidak terbuat di agent_work_sessions
        // (Kita bisa memverifikasi index status tetap ada)
        // Di SQLite, kita bisa mengambil list index dari SQLite system table
        $indices = \Illuminate\Support\Facades\DB::select("PRAGMA index_list('agent_work_sessions')");
        
        $indexNames = array_map(function ($idx) {
            return $idx->name;
        }, $indices);

        // Harus ada idx_ws_status
        $this->assertTrue(in_array('idx_ws_status', $indexNames), 'idx_ws_status index should exist');
        // idx_ws_user_date HARUSNYA TIDAK ADA (karena sudah dihapus agar tidak duplikat dengan index default)
        $this->assertFalse(in_array('idx_ws_user_date', $indexNames), 'idx_ws_user_date index should not exist');
    }
}
