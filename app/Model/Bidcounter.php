<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Bidcounter extends Model
{
    /**
     * @var string
     */
    protected $table = 'counters';
    
       
       public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }

       public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

        public function hoteldetail()
        {
            return $this->belongsTo(Hoteldetail::class, 'hoteldetail_id');
        }

}
