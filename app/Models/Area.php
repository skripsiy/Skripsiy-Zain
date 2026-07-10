<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['name'];

    public function witels()
    {
        return $this->hasMany(Witel::class);
    }
}
