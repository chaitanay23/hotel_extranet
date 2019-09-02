<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Hotelbarprice extends Model
{
    /**
     * @var string
     */
    protected $table = 'hotelbarprices';
    
    /**
     * @var array
     */
    protected $fillable = ['hotel_id','user_id','category_id','pricelist_id','selling_price'];
    protected $hidden = ['created_at', 'updated_at'];
  

         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

          
            public function pricelist()
        {
            return $this->belongsTo(Pricelist::class, 'pricelist_id');
        }

}
