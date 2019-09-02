<?php namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    
    protected $table = 'contacts';
    
     public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }

}
