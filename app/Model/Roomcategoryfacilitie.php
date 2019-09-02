<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Roomcategoryfacilitie extends Model
    {

    	protected $table ='roomcategoryfacilities';
        protected $fillable = ['name'];




			    public static function getcategoryfacilitiesList()
				    {
				        $data = Roomcategoryfacilitie::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            $data_new[$value->id] = $value->name; 
				        }
				        return $data_new;
				    }


				    

    }
