<?php

 namespace App\Model; 
 use Illuminate\Database\Eloquent\Model;

    class Commission extends Model
    {

    	protected $fillable = ['commissions'];
    	public function HotelName()
    	  {
    	 	return $this->belongsTo(Hotel::class,'hotel_id');
    	  }
    }
