<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    public function categ()
    {
        $this->belongsTo(Producer::class);
    }

    public function vihecles()
    {
        return $this->hasMany(Vihecle::class);
    }
}
