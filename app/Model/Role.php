<?php 
namespace App\Model;
use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
    protected $table = 'roles';
    protected $hidden = ['created_at', 'updated_at'];


    public static function getRoleList()
    {
        $data = Role::select()->where('id',3)->orwhere('id',4)->get();
        $data_new = array();
        foreach ($data as $key => $value) {
            $data_new[$value->id] = $value->label; 
        }
        return $data_new;

    }

    
         
}
