<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model;

class RestaurantCuisine extends Model
{

    protected $table = 'restaurantcuisines';
    protected $hidden = ['created_at', 'updated_at'];

       
       public static function getListRestaurantCuisine()
                    
                    {
                        $data = RestaurantCuisine::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }
         
         
     
    
}
