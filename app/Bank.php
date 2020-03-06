<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

    class Bank extends Model
    {
    	protected $table = 'banks';
        protected $fillable = ['name'];




         public static function getList()
				    {
				        $data = bank::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            $data_new[$value->id] = $value->name; 
				        }
				        return $data_new;

				    }

			     




    }
