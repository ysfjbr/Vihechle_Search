<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    public function vihecles()
    {
        return $this->hasMany(Vihecle::class);
    }
}
