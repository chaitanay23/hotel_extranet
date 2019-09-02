<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Ratemapping extends Model
{
    /**
     * @var string
     */
    protected $table = 'ratemappings';
    
    /**
     * @var array
     */
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
