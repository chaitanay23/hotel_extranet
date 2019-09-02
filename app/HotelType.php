<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HotelType extends Model
{
    
    protected $table = 'hoteltypes';

    protected $fillable = ['name'];
}
