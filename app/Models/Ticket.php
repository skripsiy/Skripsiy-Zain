<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'assignby', 'solvedby', 'escalationStatus', 'resolved_by_agent', 'hasil_pengecekan',
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
     * Get the user who assigned this ticket
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assignby', 'email');
    }

    /**
     * Get the user who solved this ticket
     */
    public function solvedBy()
    {
        return $this->belongsTo(User::class, 'solvedby', 'email');
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


