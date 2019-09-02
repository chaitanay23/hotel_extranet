<?php 

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class RestaurantUser extends Model
{

	  	   protected $table = 'admins';
	       public function setPasswordAttribute($password)
		        {
		            $this->attributes['password'] = bcrypt($password);
		        }
		    
		   public function roles()
            {
                return $this->belongsToMany(Role::class, 'admin_role', 'admin_id');
            }




             public static function getListRestaurantVendor()
                    {
                        $data = RestaurantUser::where('status', 1)->where('role_id', 5)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            $data_new[$value->id] = $value->name; 
                        }
                        return $data_new;
                    }

              public static function getListRestaurantForVendor($id)
                    {
                        $data = RestaurantUser::where('status', 1)->where('super_user_id',$id)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) {
                            $data_new[$value->id] = $value->name; 
                        }
                        return $data_new;
                    }
}
