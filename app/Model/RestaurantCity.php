<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class RestaurantCity extends Citie
{

          public static function getListRestaurantCity()
                    {
                        
                        $data = RestaurantCity::where('status2', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {

                            $select_state = State::where('id',$value->state_id)->first();
                            $data_new[$value->id] = $value->name.' , '.$select_state->name; 
                        }

                        return $data_new;

                    }
     
    
}
