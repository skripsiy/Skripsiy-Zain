<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketEscalation extends Model
{
    protected $fillable = [
        'ticket_id',
        'escalated_to',
        'escalated_via',
        'contact',
        'respon_be',
        'status',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
