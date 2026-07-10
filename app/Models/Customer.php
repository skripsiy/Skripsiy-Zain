<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone_number', 'password'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }
}
