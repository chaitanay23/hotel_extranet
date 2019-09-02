<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

    class Category extends Model
    {
        protected $fillable = ['name'];

                


                 public static function getList()
				    {
				        $data = category::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            
				            // $data_new[$value->id] = $value->name; 
				            $data_new[$value->name] = $value->name; 
				        }

				        return $data_new;

				    }




				    public static function getListid()
				    {

				    	


				        $data = Category::where('status',1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            
				           
				            $data_new[$value->id] = $value->name; 
				        }

				        return $data_new;

				    }


    }
