<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ====================================================================================
// SCHEDULING UNTUK CEK TIKET SLA
// ====================================================================================
// Command tickets:check-sla dijalankan setiap 1 jam untuk memeriksa ticket
// yang mendekati batas waktu SLA (24 jam).
//
// Untuk menjadwalkan:
// - Setiap 1 jam: ->hourly()
// - Setiap 5 menit: ->everyFiveMinutes()
// - Setiap menit: ->everyMinute()
//
// Log disimpan di: storage/logs/sla-checker.log
// ====================================================================================

Schedule::command('tickets:check-sla')
        ->hourly()
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/sla-checker.log'));

Schedule::command('tickets:sync-telkomsel')
        ->hourly()
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/telkomsel-sync.log'));

// ====================================================================================
// SIMULASI REAL-TIME TICKET FLOW
// ====================================================================================
// Command tickets:inject dijalankan setiap 2 menit untuk inject tiket baru
// secara otomatis dengan random urgency, topik, dan data pelanggan.
// Ini mensimulasikan alur tiket real-time jika website ini di-deploy secara nyata.
//
// Untuk menjalankan scheduler di local: php artisan schedule:work
// Log disimpan di: storage/logs/ticket-injector.log
// ====================================================================================

Schedule::command('tickets:inject')
        ->everyTwoMinutes()
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/ticket-injector.log'));
