<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class RestaurantState extends State
{
        protected $table = 'states';

         public static function getListRestaurantState()
                    {
                        $data = RestaurantState::where('status2', 1)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) {
                            # code...
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }
}
