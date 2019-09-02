<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class RestaurantCouponOrder extends Model
	{

		protected $table = 'restaurantcouponorders';


		public function user()
	    {
		    return $this->belongsTo(User::class, 'user_id');
	    }

	    public function restaurantcoupon()
	    {
		    return $this->belongsTo(RestaurantCoupon::class, 'restaurantcoupon_id');
	    }
	    
	    public function restaurant()
	    {
		    return $this->belongsTo(Restaurant::class, 'restaurant_id');
	    }

	}
