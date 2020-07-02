<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vihecle extends Model
{
    public function subcateg()
    {
        return $this->belongsTo(Subcateg::class);
    }

    public function series()
    {
        return $this->belongsTo(Series::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'vihecle__parts');
    }
}
