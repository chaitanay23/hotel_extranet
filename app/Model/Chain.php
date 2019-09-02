<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Chain extends Model
    {
        protected $fillable = ['chains'];





			     public static function getList()
				    {
				        $data = Chain::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            # code...
				            $data_new[$value->id] = $value->name; 
				        }

				        return $data_new;

				    }




    }
