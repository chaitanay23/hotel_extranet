<?php 

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class HotelUser extends Model
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
            public static function getAdminUserList()
            {
                $data = HotelUser::select()->where(['user_role_define'=>2,'role_id'=>4])->take(10)->get();
    
                $data_new = array();
                
                foreach ($data as $key => $value) 
                {
                    $data_new[$value->id] = $value->name; 
                }
                return $data_new;
    
            }
			/*BOF Added By Ashwani For User listing on 29/09/2018*/
			public static function getVendorlist($user, $keyword)
                    {
						$limit = env('AUTOSUGGESTION_LIMIT'); //For display number of record. it's define in .env file
						if(isset($user['superuser']) && $user['superuser'] == 1){
							$data = HotelUser::select()->where('name', 'like', '%' . $keyword . '%')->where('status', 1)->where('role_id','4')->where('user_role_define','2')->limit($limit)->get();						
						}else if(isset($user['localuser']) && $user['localuser'] == 1){
							$data = HotelUser::select()->where('name', 'like', '%' . $keyword . '%')->where('status', 1)->where('role_id','3')->limit($limit)->get();						
						}
                        $data_new = array();

                        foreach ($data as $key => $value) 
                        {
                            $data_new[$value->id] = $value->name; 
                        }
                             return $data_new;

                    }
					/*BOF Added By Ashwani For User listing on 29/09/2018*/
}
