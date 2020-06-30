<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vihecle extends Model
{
    public function subcateg()
    {
        $this->belongsTo(Subcateg::class);
    }

    public function series()
    {
        $this->belongsTo(Series::class);
    }

    public function country()
    {
        $this->belongsTo(Country::class);
    }

    public function sales()
    {
        $this->belongsTo(Sales::class);
    }
}
