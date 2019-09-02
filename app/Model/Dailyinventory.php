<?php 
namespace App\Model;

use App\Admin;
use Illuminate\Database\Eloquent\Model;


class Dailyinventory extends Model
{
    /**
     * @var string
     */
    protected $table = 'dailyinventories';
    
    /**
     * @var array
     */
    protected $fillable = ['hotel_id','date','rooms','booked','flag','category_id'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];



         public function hotel()
        {
            return $this->belongsTo(Hotel::class, 'hotel_id');
        }


        public function category()
        {
            return $this->belongsTo(Category::class, 'category_id');
        }



}
