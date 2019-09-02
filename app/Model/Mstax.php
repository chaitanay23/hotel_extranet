<?php

 namespace App\Model; 
 use Illuminate\Database\Eloquent\Model;

    class Mstax extends Model
    {

    	protected $table = 'mstaxes';
   // protected $fillable = ['id', 'city_id','status'];
    protected $hidden = ['created_at', 'updated_at'];


    	// public function HotelName()
    	//   {
    	//  	return $this->belongsTo(Hotel::class,'hotel_id');
    	//   }
    }