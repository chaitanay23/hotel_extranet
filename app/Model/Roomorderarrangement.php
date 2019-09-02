<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Roomorderarrangement extends Model
{
    /**
     * @var string
     */
   protected $table = 'hoteldetails';
    
    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];
  

         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

        
}