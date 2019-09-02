<?php 
namespace App\Model;

use Auth;
use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Hotel extends Model
{
    /**
     * @var string
     */
    protected $table = 'hotels';

    /**
     * @var array
     */
    protected $fillable = ['title', 'contact', 'min_price', 'max_price', 'location_id', 'picture' ];

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
        if(is_array($pictures)){
        $this->attributes['pictures'] = implode(',', $pictures);
        }
    }

        public function citie()
        {
            return $this->belongsTo(Citie::class, 'location_id');
        }
        public function trend()
        {
            return $this->belongsTo(Trend::class, 'type_id');
        }

        public function region()
        {
            return $this->belongsTo(Region::class, 'region_id');
        }


         public function admin()
        {
            return $this->belongsTo(Admin::class, 'user_id');
        }



         public function area()
        {
            return $this->belongsTo(Area::class, 'area_id');
        }



   

      public function attributes()
        {
            return $this->belongsToMany(Attribute::class, 'attribute_hotel', 'hotel_id');
        }



         public static function getHotelList($id)
                    {
                        $data = Hotel::where('user_id', $id)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) {
                           
                            $data_new[$value->id] = $value->title; 
                        }

                        return $data_new;

                    }



                    public static function getHotelListArray($id)
                    {
                         
                        $data = Hotel::whereIn('user_id', $id)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                           
                            $data_new[$value->id] = $value->title; 
                        }

                        return $data_new;

                    }

       





    //      public function companies()
    // {
    //     return $this->belongsToMany(Company::class, 'company_contact', 'contact_id');
    // }

   

}
