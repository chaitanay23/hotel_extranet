<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
class Region extends Model
{
    

    protected $table = 'regions';
    protected $fillable = ['name', 'city_id','status'];
    protected $hidden = ['created_at', 'updated_at'];


			    public function citie()
			         {
			            return $this->belongsTo(Citie::class, 'city_id');
			         }

         public static function getRegionList()
				    {

				        $data = Region::where('status1', 1)->get();
				        $data_new = array();
				        foreach ($data as $key => $value) {
				        	$select_city = Citie::where('id',$value->city_id)->first();

				            $data_new[$value->id] = $value->name.' , '.$select_city->name; 
				        }
				        return $data_new;
				    }


}
