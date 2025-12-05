<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentWorkSession extends Model
{
    protected $fillable = [
        'user_id',
        'shift_start',
        'shift_end',
        'total_online_seconds',
        'total_aux_seconds',
        'aux_remaining_seconds',
        'status',
        'current_session_start',
        'work_date'
    ];

    protected $casts = [
        'shift_start' => 'datetime',
        'shift_end' => 'datetime',
        'current_session_start' => 'datetime',
        'work_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
