<?php 
namespace App;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Bidcounter extends Model
{
    /**
     * @var string
     */
    protected $table = 'counters';
    
    protected $fillable = ['user_id', 'hotel_id', 'hoteldetail_id'];
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
            return $this->belongsTo(Room::class, 'hoteldetail_id');
        }

}
