<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Channelpartner extends Model
{
   
    protected $table = 'hotelapiinteractions';
    protected $hidden = ['created_at', 'updated_at'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

}