<?php

namespace App;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;
use App\Room;
use App\Admin;
use App\Inventory;

class Hotel extends Model
{
	use NullableFields;

    protected $table = 'hotels';

    protected $fillable = ['user_id','rm_user_id','revenue_user','hotel_branch_name','title','hoteltype_id','rating','display_name','location_id','area_id','region_id','address','latitude','longitude','hote_description','check_in_time','check_out_time','picture','pictures','note','own_property'];

    protected $nullable = ['display_name','hote_description'];

    public function admin(){
    	return $this->belongsTo('App\Admin');
    }
    public function room(){
    	return $this->hasMany('App\Room', 'hotel_id');
    }
    public function inventory(){
        return $this->hasMany('App\Inventory');
    }
}
