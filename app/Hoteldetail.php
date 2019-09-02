<?php 
namespace App;
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
    protected $fillable = ['hotel_id','room_id','category_id','price','picture','pictures'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];


    //  public function getPicturesAttribute($value)
    // {
    //     return preg_split('/,/', $value, -1, PREG_SPLIT_NO_EMPTY);
    // }

    // public function setPicturesAttribute($pictures)
    // {
    //     $this->attributes['pictures'] = implode(',', $pictures);
    // }


    

    /* public function rooms()
        {
            return $this->belongsToMany(Room::class, 'room_hotel', 'hotel_id');
            
        } */


         public function hotel()
        {
            return $this->belongsTo('App\hotel', 'hotel_id');
        }

          
        //     public function room()
        // {
        //     return $this->belongsTo(Room::class, 'room_id');
        // } 


          public function category()
        {
            return $this->belongsTo('App\Category', 'category_id');
        } 


              public function roomcategoryfacilities()
            {
                return $this->belongsToMany('App\Roomcategoryfacilitie','roomcategoryfacilitie_hoteldetail');
            }
            
            public function roominclusions()
        {
            return $this->belongsToMany('App\Roominclusion','roominclusion_hoteldetail');
        }








}