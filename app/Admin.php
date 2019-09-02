<?php

namespace App;

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use App\Hotel;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use NullableFields;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password','primary_email','user_role_define','super_user_id','super_user_name','user_level','mou','mobile','profile_pic'
    ];

    protected $nullable = [
        'super_user_id','super_user_name','mou'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function super_user()
    {
        return $this->hasOne('App\Admin');
    }

    public function hotel_user()
    {
        return $this->belongsTo(Hotel::class);
    }
}
