<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
  public function hotel()
        {
            return $this->belongsTo('App\hotel', 'hotel_id');
        }
   public function hoteldetail()
        {
            return $this->belongsTo('App\Hoteldetail', 'hoteldetail_id');
        }
   // public function category()
   //      {
   //          return $this->belongsTo('App\Category', 'category_id');
   //      }



}