<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;
use DB;


class Hotelstatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'hotels';

    public static function hoteldetail_count($hotel_id)
    {
        $hoteldetail = DB::table('hoteldetails')->where(['hotel_id'=>$hotel_id,'status'=>1])->count();

        if($hoteldetail > 0)
        {
            $data = 'Yes';
        }
        else
        {
            $data = 'No';
        }

        return $data;
    }
    
       
       

}