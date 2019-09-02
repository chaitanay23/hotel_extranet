<?php 
namespace App;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Negotiable extends Model
{
    /**
     * @var string
     */
    protected $table = 'negotiables';
    
       
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
            return $this->belongsTo(Room::class, 'category_id');
        }

}
