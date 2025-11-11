<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $primaryKey = 'idTicket';
    
    protected $fillable = [
        'datereport', 'jenisTicket', 'notelpCust', 'password', 'namacust',
        'idlaporan', 'detailticket', 'gamas', 'lapul', 'gaul', 'resume',
        'klasifikasi', 'topic', 'topicDetail', 'noSC', 'statusSC',
        'validateClose', 'reasonnoODS', 'eksalasiTicket', 'eksalasiVia',
        'PIC', 'contact', 'responBE', 'description', 'reportedpriority',
        'datesolved', 'THT', 'status', 'regional', 'witel', 'condition',
        'assignby', 'solvedby', 'escalationStatus'
    ];

    protected $casts = [
        'datereport' => 'date',
        'datesolved' => 'date',
        'THT' => 'datetime',
    ];
}
