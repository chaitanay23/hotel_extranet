<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class RestaurantArea extends Area
{

	          public static function getListRestaurantArea()
                    {
                        $data = RestaurantArea::where('status2', 1)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) {
                            # code...
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }



                    public static function getListRestaurantfilterArea($id)
                    {
                        $data = RestaurantArea::where('city_id',$id)->where('status2', 1)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) {
                            # code...
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }
    
}
