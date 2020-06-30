<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function vihecles()
    {
        return $this->hasMany(Vihecle::class);
    }
}
