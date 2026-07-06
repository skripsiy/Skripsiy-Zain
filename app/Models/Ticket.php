<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ticket extends Model
{
    use LogsActivity;

    protected $primaryKey = 'idTicket';
    
    protected $fillable = [
        'datereport', 'jenisTicket', 'notelpCust', 'password', 'namacust',
        'idlaporan', 'detailticket', 'gamas', 'lapul', 'gaul', 'resume',
        'klasifikasi', 'topic', 'topicDetail', 'noSC', 'statusSC',
        'validateClose', 'reasonnoODS', 'eksalasiTicket', 'eksalasiVia',
        'PIC', 'contact', 'responBE', 'description', 'reportedpriority',
        'datesolved', 'THT', 'status', 'regional', 'witel', 'condition',
        'assigned_to_user_id', 'solved_by_user_id', 'escalationStatus', 'resolved_by_agent', 'hasil_pengecekan', 'attachment',
        // Routing fields
        'channel', 'source_system', 'pool_id', 'urgency_level', 'division_target', 'auto_assigned_at',
    ];

    protected $casts = [
        'datereport'      => 'date',
        'datesolved'      => 'date',
        'THT'             => 'datetime',
        'auto_assigned_at'=> 'datetime',
        'urgency_level'   => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Invalidasi cache dashboard setiap ada perubahan tiket.
     * Agar statistik dashboard tidak stale saat data berubah.
     */
    protected static function booted(): void
    {
        $clearCache = function ($ticket) {
            // Hapus cache Admin
            foreach (['today', 'week', 'month', 'quarter'] as $filter) {
                Cache::forget("admin_dashboard_stats_{$filter}");
                Cache::forget("admin_chart_{$filter}");
            }

            // Hapus cache Agent yang bersangkutan
            $agentIds = array_filter([$ticket->assigned_to_user_id, $ticket->solved_by_user_id]);
            foreach ($agentIds as $agentId) {
                foreach (['today', 'week', 'month', 'quarter'] as $filter) {
                    Cache::forget("agent_dashboard_stats_{$agentId}_{$filter}");
                }
            }

            // Hapus cache Team Leader (TL dashboard menggunakan md5 cache key dinamis)
            // Di lokal/testing, kita bisa menggunakan Cache::flush() jika menggunakan driver file/redis
            // untuk membersihkan sisa cache agar data selalu ter-update
            try {
                // Untuk project skripsi, pembersihan cache total aman dilakukan saat terjadi penulisan
                Cache::flush();
            } catch (\Exception $e) {
                // Fail-safe jika flush diblokir driver tertentu
            }
        };

        static::saved(function ($ticket) use ($clearCache) {
            $clearCache($ticket);
        });

        static::deleted(function ($ticket) use ($clearCache) {
            $clearCache($ticket);
        });
    }

    /**
     * Get the user this ticket is assigned to
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    /**
     * Get the user who solved this ticket
     */
    public function solvedBy()
    {
        return $this->belongsTo(User::class, 'solved_by_user_id');
    }

    // ─────────────────────────────────────────────
    // Urgency Helpers
    // ─────────────────────────────────────────────

    /**
     * Label urgency tier berdasarkan urgency_level (1–5)
     */
    public function getUrgencyLabelAttribute(): string
    {
        return match ((int) $this->urgency_level) {
            5 => 'VVIP/Management',
            4 => 'HVC',
            3 => 'Super Emergency',
            2 => 'Emergency',
            default => 'Low Emergency',
        };
    }

    /**
     * CSS class untuk badge urgency
     */
    public function getUrgencyBadgeClassAttribute(): string
    {
        return match ((int) $this->urgency_level) {
            5 => 'urgency-vvip',
            4 => 'urgency-hvc',
            3 => 'urgency-super',
            2 => 'urgency-emergency',
            default => 'urgency-low',
        };
    }

    /**
     * Label nama divisi target
     */
    public function getDivisionLabelAttribute(): string
    {
        return match ($this->division_target) {
            'area'     => 'Area',
            'besfixed' => 'Besfixed',
            'saltik'   => 'Saltik',
            default    => '-',
        };
    }
}


