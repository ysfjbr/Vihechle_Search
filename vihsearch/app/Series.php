<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    public function producer()
    {
        return $this->belongsTo(Producer::class);
    }

    public function vihecles()
    {
        return $this->hasMany(Vihecle::class);
    }
}
