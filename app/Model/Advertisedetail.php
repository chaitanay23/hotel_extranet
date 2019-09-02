<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Advertisedetail extends Model
{
    
    protected $table = 'advertisedetails';

   
    protected $fillable = ['category', 'city', 'from_date', 'end_date', 'picture','status'];

    protected $hidden = ['created_at', 'updated_at'];



         public function advertisetype()
            {
                return $this->belongsTo(Advertisetype::class, 'advertisetype_id');
            }


           public function cities()
            {
                return $this->belongsToMany(Citie::class, 'city_advertisedetail', 'advertisedetail_id');
            }



        /*  public function couponcategory()
        {
            return $this->belongsTo(Couponcategory::class, 'couponcategory_id');
        } */


        /* public static function getList()
                     {
                        $data = Advertisetype::where('status', 1)->get();
                        $data_new = array();
                        foreach ($data as $key => $value) 
                        {
                            $data_new[$value->name] = $value->name; 
                        }
                        return $data_new; 
                    } 
                    */

        
   

}
