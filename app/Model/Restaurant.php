<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;

//use Baum\Extensions\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
	{
   
    protected $table = 'restaurants';
    protected $hidden = ['created_at', 'updated_at'];

    // protected $dates =  ['deleted_at'];


             /*  public function citie()
        {
            return $this->belongsTo(Citie::class, 'location_id');
        } */


            public function getPicturesAttribute($value)
			    {
			        return preg_split('/,/', $value, -1, PREG_SPLIT_NO_EMPTY);
			    }

			    public function setPicturesAttribute($pictures)
			    {
			        $this->attributes['pictures'] = implode(',', $pictures);
			    }




                 public function Getrestaurentcityname()
			        {
			            return $this->belongsTo(Citie::class, 'city_id');
			        }


			    public function Getrestaurentregionname()
			        {
			            return $this->belongsTo(Region::class, 'region_id');
			        }

			      public function Getrestaurentareaname()
			        {
			            return $this->belongsTo(Area::class, 'area_id');
			        }


    			public function restaurantcuisines()
			        {
			           return $this->belongsToMany(RestaurantCuisine::class, 'restaurantcuisine_restaurant', 'restaurant_id');
			        }
			        

			        public function restaurantfeatures()
			        {
			           return $this->belongsToMany(RestaurantFeature::class, 'restaurantfeature_restaurant', 'restaurant_id');
			        }


			        public function restauranttags()
			        {
			           return $this->belongsToMany(Restauranttag::class, 'restauranttag_restaurant', 'restaurant_id');
			        }


			        public function restaurantoutlettypes()
			        {
			           return $this->belongsToMany(RestaurantOutlettype::class, 'restaurantoutlettype_restaurant', 'restaurant_id');
			        }




			        public static function getListRestaurantName()
                    {
                        $data = Restaurant::where('status', 1)->get();

                        $data_new = array();
                        foreach ($data as $key => $value) {
                            
                            $data_new[$value->id] = $value->name; 
                        }

                        return $data_new;

                    }



                    public static function getRestaurantListArray($id)
                    {
                        $data = Restaurant::whereIn('admin_id', $id)->where('status',1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            $data_new[$value->id] = $value->name; 
                        } 
                        return $data_new;

                    }



                     public function getrestaurantname()
					        {
					            return $this->belongsTo(RestaurantContact::class, 'restaurant_id');
					        }


			public function admin()
			        {
			            return $this->belongsTo(Admin::class, 'user_id');
			        }


			       


			       
			        /*
			          public function getrestaurantfeaturename()
			            {
			                return $this->belongsToMany('App\Model\RestaurantFeature');
			            }
			         */
			           

}
