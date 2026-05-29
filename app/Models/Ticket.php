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
        'assignby', 'solvedby', 'escalationStatus', 'resolved_by_agent', 'hasil_pengecekan'
    ];

    protected $casts = [
        'datereport' => 'date',
        'datesolved' => 'date',
        'THT' => 'datetime',
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
}

