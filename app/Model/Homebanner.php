<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Homebanner extends Model
{
   
    protected $table = 'homebanners';
    protected $hidden = ['created_at', 'updated_at'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function homebannermasters()
    {
        return $this->belongsTo(Homebannermaster::class, 'homebannermasters_id');
    }
       

}
