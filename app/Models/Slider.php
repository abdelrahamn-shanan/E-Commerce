<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliderimages';
    protected $guarded = [];

    public function getImageAttribute($val) // accessors
    {
        return ($val !== null) ? asset('assets/'.$val) : '';
    }
}
