<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Maximojointeraction extends Model
{
   
    protected $table = 'maximojointeractions';
    protected $hidden = ['created_at', 'updated_at'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function hoteldetail()
    {
        return $this->belongsTo(Hoteldetail::class, 'hoteldetail_id');
    }

}
