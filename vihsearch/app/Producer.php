<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    public function series()
    {
        return $this->hasMany(Series::class);
    }

    public function vihecles()
    {
        return $this->hasMany(Vihecle::class);
    }
}
