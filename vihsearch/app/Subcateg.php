<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcateg extends Model
{
    protected $guarded=[];

    public function categ()
    {
        $this->belongsTo(Categ::class);
    }

    public function vihecles()
    {
        return $this->hasMany(Vihecle::class);
    }
}