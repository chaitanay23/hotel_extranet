<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\Admin\Traits\OrderableModel;

class Tax extends Model
{
    

    protected $table = 'taxes';
   // protected $fillable = ['id', 'city_id','status'];
    protected $hidden = ['created_at', 'updated_at'];


    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function hoteldetail()
    {
        return $this->belongsTo(Hoteldetail::class, 'hoteldetail_id');
    }


    public function gsttaxes()
    {
        return $this->belongsTo(Gsttax::class, 'gsttaxes_id');
    }

    


}
