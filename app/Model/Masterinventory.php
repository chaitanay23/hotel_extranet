<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Masterinventory extends Model
{
    /**
     * @var string
     */
    protected $table = 'masterinventories';
    
    /**
     * @var array
     */
    protected $fillable = ['user_id','hotel_id','start_date','end_date','no_of_rooms'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];



         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

         public function hoteldetail()
        {
            return $this->belongsTo(Hoteldetail::class, 'hoteldetail_id');
        }

}
