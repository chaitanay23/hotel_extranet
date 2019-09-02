<?php

namespace App;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use NullableFields;

    protected $table = 'commissions';
    protected $fillable = ['hotel_id','commission','pbc','comment','tds','magicspree_fee','additional_discount','max_discount'];
    protected $nullable = ['pbc','tds'];
}
