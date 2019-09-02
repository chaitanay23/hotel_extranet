<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class State extends Model
{
    

    protected $table = 'states';

    protected $fillable = ['name', 'country_id','status'];
    protected $hidden = ['created_at', 'updated_at'];




    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }


    public function scopeDefaultSort($query)
    {
        return $query->where('id', 1);
    }
   


   public static function getList()
    {
        $data = state::where('status', 1)->get();

        $data_new = array();
        foreach ($data as $key => $value) {
            # code...
            $data_new[$value->id] = $value->name; 
        }

        return $data_new;

    }





    /* public function citie()
    {
        return $this->belongsTo(Citie::class, 'location_id');
    } */

    

    

}
