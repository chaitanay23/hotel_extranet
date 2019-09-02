<?php

namespace App;
use App\Hotel;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'dailyinventories';
    protected $fillable = ['hotel_id','date','rooms_cp','rooms_ep','category_id','hoteldetails_id','booked','single_occupancy_price_cp','double_occupancy_price_cp','extra_adult_cp','child_price_cp','single_occupancy_price_ep','double_occupancy_price_ep','extra_adult_ep','child_price_ep','cp_status','ep_status'];

    public function hotel(){
    	$this->belongsTo('\App\Hotel');
    }
}
