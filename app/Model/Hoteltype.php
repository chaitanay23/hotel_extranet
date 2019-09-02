<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Hoteltype extends Model
    {
        protected $fillable = ['Hoteltypes'];





			     public static function getList()
				    {
				        $data = Hoteltype::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            # code...
				            $data_new[$value->id] = $value->name; 
				        }

				        return $data_new;

				    }




    }
