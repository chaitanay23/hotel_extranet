<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class RestaurantFeature extends Model
{

    protected $table = 'restaurantfeatures';
    protected $hidden = ['created_at', 'updated_at'];


     public static function getListRestaurantFeature()
                    {
                        $data = RestaurantFeature::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            # code...
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }
         
     
    
}
