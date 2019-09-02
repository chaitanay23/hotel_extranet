<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class Area extends Model
{
    

    protected $table = 'areas';

    protected $fillable = ['name', 'city_id','status'];
    protected $hidden = ['created_at', 'updated_at'];


    public function citie()
         {
            return $this->belongsTo(Citie::class, 'city_id');
         }

    public function region()
         {
            return $this->belongsTo(Region::class, 'region_id');
         }

     
     
	    


         public static function getList()
				    {
				        $data = area::where('status', 1)->get();

				        $data_new = array();
				        foreach ($data as $key => $value) {
				            $data_new[$value->id] = $value->name; 
				        }
				        return $data_new;

				    }


}
