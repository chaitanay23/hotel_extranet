<?php 
namespace App\Model; 
use Illuminate\Database\Eloquent\Model;

class RestaurantTag extends Model
{

    protected $table = 'restauranttags';
    protected $hidden = ['created_at', 'updated_at'];
       
       public static function getListRestaurantTag()
                    
                    {
                        $data = RestaurantTag::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }
         
         
     
    
}
