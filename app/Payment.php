<?php 
namespace App;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    /**
     * @var string
     */
    protected $table = 'payments';
    
       
       public function booking()
        {
            return $this->belongsTo(Booking::class, 'booking_id');
        }

}
