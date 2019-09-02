<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Potential extends Model
{
    /**
     * @var string
     */
    protected $table = 'potentials';
    
    /**
     * @var array
     */
    protected $fillable = ['hotel_id','season_id','popularity','inventory'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];


    


    

    /* public function rooms()
        {
            return $this->belongsToMany(Room::class, 'room_hotel', 'hotel_id');
            
        } */


         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

          
            public function season()
        {
            return $this->belongsTo(Season::class, 'season_id');
        } 




       






}
