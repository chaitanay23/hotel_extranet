<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddonType extends Model
{
    protected $table = 'couponcategories';
    protected $fillable = ['title','picture','list_img','description','amount','tagline'];
}
