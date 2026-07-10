<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Witel extends Model
{
    protected $fillable = ['area_id', 'name'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'witel_id');
    }
}
