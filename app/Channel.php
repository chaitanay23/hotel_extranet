<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	protected $table = 'hotelapiinteractions';
	protected $fillable = ['hotel_id','hotelkey','hotelpassword','channel_partner'];
}
