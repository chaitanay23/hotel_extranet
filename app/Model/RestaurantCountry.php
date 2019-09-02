<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class RestaurantCountry extends Country
{
         protected $table = 'countries';

           public static function getListRestaurantCountry()
                    {
                        $data = RestaurantCountry::where('status2',1)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) {
                           
                            $data_new[$value->id] = $value->title; 
                        }

                        return $data_new;

                    }
     

}
