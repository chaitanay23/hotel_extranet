<?php 
namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
   
    protected $table = 'reviews';
    protected $hidden = ['created_at', 'updated_at'];

    public function hotel()
    {
	    return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function user()
    {
	    return $this->belongsTo(User::class, 'user_id');
    }

}