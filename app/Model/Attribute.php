<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Attribute extends Model
    {
        protected $fillable = ['name','value'];


			  /* public function hotels()
			    {
			        return $this->belongsToMany(Hotel::class);
			    } */



			    public static function getList()
				    {
				        $data = attribute::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            $data_new[$value->id] = $value->value; 
				        }
				        return $data_new;
				    }


				    

    }
