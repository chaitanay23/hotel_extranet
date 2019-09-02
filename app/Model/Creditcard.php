<?php

 namespace App\Model; 
 use Illuminate\Database\Eloquent\Model;

    class Creditcard extends Model
    {

    	protected $table = 'creditcards';
   // protected $fillable = ['id', 'city_id','status'];
    protected $hidden = ['created_at', 'updated_at'];


    	// public function HotelName()
    	//   {
    	//  	return $this->belongsTo(Hotel::class,'hotel_id');
    	//   }
    }