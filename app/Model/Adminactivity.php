<?php 
namespace App\Model;

use App\Admin;
use App\Model\Hotel;
use Illuminate\Database\Eloquent\Model;


class Adminactivity extends Model
{
    /**
     * @var string
     */
    protected $table = 'adminactivities';
    
       
   	public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }


    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function hoteldetail()
    {
        return $this->belongsTo(Hoteldetail::class, 'hoteldetail_id');
    }

}
