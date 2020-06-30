<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categ extends Model
{
    //
    protected $guarded=[];

    public function subcategs()
    {
        return $this->hasMany(Subcateg::class);
    }

    public function vihecles()
    {
        return $this->hasMany(Vihecle::class);
    }
}
