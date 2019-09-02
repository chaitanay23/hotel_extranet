<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class RestaurantCoupon extends Model
{
    
    protected $table = 'restaurantcoupons';
   
    /*
    
    protected $fillable = ['hotel_id', 'coupon_type', 'coupon_name', 'code', 'discount', 'start_date','end_date','coupon_min_order','status','amount','description'];
    protected $hidden = ['created_at', 'updated_at'];
    
    */



       public function getRestaurantname()
        {
            return $this->belongsTo(Restaurant::class, 'restaurant_id');
        }

        public function getRestaurantCouponCategoryName()
                {
                    return $this->belongsTo(RestaurantCouponCategory::class, 'restaurantcouponcategory_id');
                }

        
        /*
        
        public function couponcategory()
        {
            return $this->belongsTo(Couponcategory::class, 'couponcategory_id');
        }

        */

         


        
        
   

}
