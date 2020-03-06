<?php

namespace App;
use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use NullableFields;

    protected $table = 'hoteldetails';
    protected $fillable = ['hotel_id','user_id','custom_category','max_guest_allow','max_child_allow','max_infant_allow','max_adult_allow','picture','status','cp_include','extra_adult_cost_collection'];
}
