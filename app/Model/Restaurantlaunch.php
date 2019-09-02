<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;
use DB;


class Restaurantlaunch extends Model
{
    /**
     * @var string
     */
    protected $table = 'restaurantlaunches';


    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }



}