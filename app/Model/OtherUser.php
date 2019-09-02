<?php 

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class OtherUser extends Model
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

}
