<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Coupon extends Model
{
    
    protected $table = 'coupons';

   
    protected $fillable = ['hotel_id', 'coupon_type', 'coupon_name', 'code', 'discount', 'start_date','end_date','coupon_min_order','status','amount','description'];

    protected $hidden = ['created_at', 'updated_at'];



         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

         public function couponcategory()
        {
            return $this->belongsTo(Couponcategory::class, 'couponcategory_id');
        }

         


        
        
   

}
