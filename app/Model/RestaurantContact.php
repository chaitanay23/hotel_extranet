<?php namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;

class RestaurantContact extends Model
{
    /**
     * @var string
     */
    protected $table = 'restaurantcontacts';

    protected $hidden = ['created_at', 'updated_at'];

    
     public function getRestaurantname()
        {
            return $this->belongsTo(Restaurant::class, 'restaurant_id');
        }

}
