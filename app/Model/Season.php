<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Season extends Model
    {
        protected $fillable = ['seasons'];


			  /* public function hotels()
			    {
			        return $this->belongsToMany(Hotel::class);
			    } */



			     public static function getList()
				    {
				        $data = Season::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				           
				            $data_new[$value->id] = $value->name; 
				        }

				        return $data_new;

				    }




    }
