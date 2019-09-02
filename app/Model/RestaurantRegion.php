<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RestaurantRegion extends Region
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



                    public static function getListRestaurantRegion()
                    {
                        $data = RestaurantRegion::where('status2', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) 
                        {
                            $city_name = RestaurantCity::where('id',$value->city_id)->first();
                            
                            $data_new[$value->id] = $value->name.' , '.$city_name->name; 
                        }

                        return $data_new;

                    }
    
}
