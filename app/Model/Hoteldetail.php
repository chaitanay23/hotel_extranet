<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Hoteldetail extends Model
{
    /**
     * @var string
     */
    protected $table = 'hoteldetails';
    
    /**
     * @var array
     */
    protected $fillable = ['hotel_id','room_id','category_id','single_occupancy_price','double_occupancy_price','picture','pictures'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];


     public function getPicturesAttribute($value)
    {
        return preg_split('/,/', $value, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function setPicturesAttribute($pictures)
    {
        $this->attributes['pictures'] = implode(',', $pictures);
    }


    

    /* public function rooms()
        {
            return $this->belongsToMany(Room::class, 'room_hotel', 'hotel_id');
            
        } */


         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

          
            public function room()
        {
            return $this->belongsTo(Room::class, 'room_id');
        } 


          public function category()
        {
            return $this->belongsTo(Category::class, 'category_id');
        } 


       public function roomcategoryfacilities()
        {
            return $this->belongsToMany(Roomcategoryfacilitie::class, 'roomcategoryfacilitie_hoteldetail', 'hoteldetail_id');
        }

        public function roominclusions()
        {
            return $this->belongsToMany(Roominclusion::class, 'roominclusion_hoteldetail', 'hoteldetail_id');
        }






}
