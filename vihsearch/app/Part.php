<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    public function vihecles()
    {
        return $this->belongsToMany(Vihecle::class, 'vihecle__parts');
    }
}
