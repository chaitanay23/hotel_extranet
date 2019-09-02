<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model;

class RestaurantOutlettype extends Model
{

    protected $table = 'restaurantoutlettypes';
    protected $hidden = ['created_at', 'updated_at'];
       
       public static function getListRestaurantoutlettype()
                    {
                        $data = RestaurantOutlettype::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            
                            $data_new[$value->id] = $value->name; 
                        }
                        return $data_new;

                    }
         
         
     
    
}
