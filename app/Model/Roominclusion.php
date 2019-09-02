<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Roominclusion extends Model
    {

    	protected $table ='roominclusions';
        protected $fillable = ['name'];




	    public static function getroominclusionsList()
		{
		    $data = Roominclusion::where('status', 1)->get();

		    $data_new = array();
		    foreach ($data as $key => $value) 
		    {
		        $data_new[$value->id] = $value->name; 
		    }
		       
		    return $data_new;
		}


				    

    }
