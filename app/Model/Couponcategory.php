<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Couponcategory extends Model
{
    
    protected $table = 'couponcategories';

   
    protected $fillable = ['title','picture','status'];

    protected $hidden = ['created_at', 'updated_at'];




    public static function getList()
                     {
                        $data = Couponcategory::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) 
                        {
                            $data_new[$value->id] = $value->title; 
                        }

                        return $data_new;

                    }


}
