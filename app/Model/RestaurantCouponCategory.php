<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class RestaurantCouponCategory extends Model
{
          protected $table = 'restaurantcouponcategories';
          protected $hidden = ['created_at', 'updated_at'];



          


            public function getRestaurantname()
            {
                return $this->belongsTo(Restaurant::class, 'restaurant_id');
            }

            public static function getListCouponCategory()
            {
                $data = RestaurantCouponCategory::where('status', 1)->get();

                $data_new = array();
                foreach ($data as $key => $value) 
                {
                    
                    $data_new[$value->id] = $value->name; 
                }

                return $data_new;

            }
          
}
