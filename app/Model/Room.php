<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Room extends Model
    {
        protected $fillable = ['name'];


			  /* public function hotels()
			    {
			        return $this->belongsToMany(Hotel::class);
			    } */



			     public static function getList()
				    {
				        $data = Room::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				           
				            $data_new[$value->id] = $value->name; 
				        }

				        return $data_new;

				    }




    }
