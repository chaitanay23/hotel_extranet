<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Gsttax extends Model
{
   
    protected $table = 'gsttaxes';
    protected $hidden = ['created_at', 'updated_at'];

    public static function GstList()
    {
        $data = Gsttax::where('status', 1)->get();

        $data_new = array();
        foreach ($data as $key => $value) {
            # code...
            $data_new[$value->id] = $value->slot; 
        }

        return $data_new;

    }

}